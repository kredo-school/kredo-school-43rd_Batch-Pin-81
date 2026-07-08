<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\Restaurant;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ReservationAvailabilityService
{
    public const CLEANING_MINUTES = 15;

    public function slotMinutes(Restaurant $restaurant): int
    {
        return max((int) ($restaurant->stay_duration ?? 120), 15);
    }

    public function blockMinutes(Restaurant $restaurant): int
    {
        return $this->slotMinutes($restaurant) + self::CLEANING_MINUTES;
    }

    public function durationLabel(Restaurant $restaurant): string
    {
        $stayMinutes = $this->slotMinutes($restaurant);
        $hours = intdiv($stayMinutes, 60);
        $minutes = $stayMinutes % 60;

        $parts = [];
        if ($hours > 0) {
            $parts[] = $hours . ' ' . ($hours === 1 ? 'hour' : 'hours');
        }
        if ($minutes > 0) {
            $parts[] = $minutes . ' minutes';
        }

        return implode(' ', $parts);
    }

    public function reservationEnd(Reservation $reservation, Restaurant $restaurant, bool $includeCleaning = true): Carbon
    {
        $start = Carbon::parse($reservation->reservation_date . ' ' . $reservation->reservation_time);
        $minutes = $this->slotMinutes($restaurant) + ($includeCleaning ? self::CLEANING_MINUTES : 0);

        return $start->copy()->addMinutes($minutes);
    }

    public function reservationEndTimeForDb(Restaurant $restaurant, string $date, string $time): string
    {
        return Carbon::parse($date . ' ' . $time)
            ->addMinutes($this->slotMinutes($restaurant))
            ->format('H:i:s');
    }

    public function getDayHours(Restaurant $restaurant, string $date): array
    {
        $dayName = Carbon::parse($date)->format('l');
        $hours = $this->normalizeOperatingHours($restaurant->operating_hours ?? null);

        return $hours[$dayName] ?? [
            'closed' => false,
            'shifts' => [
                ['open' => '17:00', 'close' => '22:00'],
            ],
        ];
    }

    public function normalizeOperatingHours($rawHours): array
    {
        if (is_string($rawHours)) {
            $decoded = json_decode($rawHours, true);
            $rawHours = is_array($decoded) ? $decoded : [];
        }

        if (!is_array($rawHours)) {
            $rawHours = [];
        }

        $default = [];
        foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day) {
            $dayValue = $rawHours[$day] ?? null;
            $closed = false;
            $shifts = [];

            if (is_array($dayValue)) {
                $closed = filter_var($dayValue['closed'] ?? false, FILTER_VALIDATE_BOOLEAN);

                if (isset($dayValue['shifts']) && is_array($dayValue['shifts'])) {
                    foreach ($dayValue['shifts'] as $shift) {
                        $open = $shift['open'] ?? null;
                        $close = $shift['close'] ?? null;
                        if ($open && $close && $open < $close) {
                            $shifts[] = [
                                'open' => substr($open, 0, 5),
                                'close' => substr($close, 0, 5),
                            ];
                        }
                    }
                } elseif (array_key_exists('open', $dayValue) || array_key_exists('close', $dayValue)) {
                    $open = $dayValue['open'] ?? null;
                    $close = $dayValue['close'] ?? null;
                    if ($open && $close && $open < $close) {
                        $shifts[] = [
                            'open' => substr($open, 0, 5),
                            'close' => substr($close, 0, 5),
                        ];
                    }
                } else {
                    foreach ($dayValue as $maybeShift) {
                        if (!is_array($maybeShift)) {
                            continue;
                        }
                        $open = $maybeShift['open'] ?? null;
                        $close = $maybeShift['close'] ?? null;
                        if ($open && $close && $open < $close) {
                            $shifts[] = [
                                'open' => substr($open, 0, 5),
                                'close' => substr($close, 0, 5),
                            ];
                        }
                    }
                }
            }

            if (!$closed && empty($shifts) && $day !== 'Sunday') {
                $shifts[] = ['open' => '17:00', 'close' => '22:00'];
            }

            $default[$day] = [
                'closed' => $closed,
                'shifts' => $closed ? [] : $shifts,
            ];
        }

        return $default;
    }

    public function buildOperatingHoursFromRequest(array $hours): array
    {
        $normalized = [];

        foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day) {
            $dayInput = $hours[$day] ?? [];
            $closed = isset($dayInput['closed']);
            $shifts = [];

            foreach ($dayInput as $key => $shift) {
                if ($key === 'closed' || !is_array($shift)) {
                    continue;
                }

                $open = $shift['open'] ?? null;
                $close = $shift['close'] ?? null;

                if ($open && $close && $open < $close) {
                    $shifts[] = [
                        'open' => substr($open, 0, 5),
                        'close' => substr($close, 0, 5),
                    ];
                }
            }

            $normalized[$day] = [
                'closed' => $closed,
                'shifts' => $closed ? [] : $shifts,
            ];
        }

        return $normalized;
    }

    public function generateStartTimes(Restaurant $restaurant, string $date, ?int $partySize = null): array
    {
        $dayHours = $this->getDayHours($restaurant, $date);
        if ($dayHours['closed'] || empty($dayHours['shifts'])) {
            return [];
        }

        $times = [];
        $blockMinutes = $this->blockMinutes($restaurant);

        foreach ($dayHours['shifts'] as $shift) {
            $cursor = Carbon::parse($date . ' ' . $shift['open']);
            $close = Carbon::parse($date . ' ' . $shift['close']);

            while ($cursor->copy()->addMinutes($blockMinutes)->lte($close)) {
                $time = $cursor->format('H:i');

                if ($partySize === null || $this->findAvailableTable($restaurant, $date, $time, $partySize)) {
                    $times[] = $time;
                }

                $cursor->addMinutes(15);
            }
        }

        return array_values(array_unique($times));
    }

    public function findAvailableTable(Restaurant $restaurant, string $date, string $time, int $partySize, ?int $ignoreReservationId = null): ?Table
    {
        return Table::where('restaurant_id', $restaurant->id)
            ->where('is_active', true)
            ->where('capacity', '>=', $partySize)
            ->orderBy('capacity')
            ->orderBy('table_name')
            ->get()
            ->first(function (Table $table) use ($restaurant, $date, $time, $partySize, $ignoreReservationId) {
                return !$this->tableHasConflict($table, $restaurant, $date, $time, $partySize, $ignoreReservationId);
            });
    }

    public function tableHasConflict(Table $table, Restaurant $restaurant, string $date, string $time, int $partySize, ?int $ignoreReservationId = null): bool
    {
        if (!$table->is_active || $partySize > $table->capacity) {
            return true;
        }

        $newStart = Carbon::parse($date . ' ' . $time);
        $newEnd = $newStart->copy()->addMinutes($this->blockMinutes($restaurant));

        $query = Reservation::where('table_id', $table->id)
            ->where('restaurant_id', $restaurant->id)
            ->whereDate('reservation_date', $date)
            ->whereIn('status', ['pending', 'confirmed']);

        if ($ignoreReservationId) {
            $query->where('id', '!=', $ignoreReservationId);
        }

        return $query->get()->contains(function (Reservation $reservation) use ($restaurant, $newStart, $newEnd) {
            $existingStart = Carbon::parse($reservation->reservation_date . ' ' . $reservation->reservation_time);
            $existingEnd = $this->reservationEnd($reservation, $restaurant, true);

            return $newStart->lt($existingEnd) && $newEnd->gt($existingStart);
        });
    }

    public function timelineBounds(Restaurant $restaurant, string $date): array
    {
        $dayHours = $this->getDayHours($restaurant, $date);

        if ($dayHours['closed'] || empty($dayHours['shifts'])) {
            return ['open' => null, 'close' => null, 'closed' => true];
        }

        $opens = collect($dayHours['shifts'])->pluck('open')->filter()->sort()->values();
        $closes = collect($dayHours['shifts'])->pluck('close')->filter()->sort()->values();

        return [
            'open' => $opens->first(),
            'close' => $closes->last(),
            'closed' => false,
        ];
    }

    public function generateTimelineSlots(string $date, ?string $displayStartTime, ?string $openTime, ?string $closeTime, int $columns = 8): array
    {
        if (!$openTime || !$closeTime) {
            return [];
        }

        $start = Carbon::parse($date . ' ' . ($displayStartTime ?: $openTime));
        $open = Carbon::parse($date . ' ' . $openTime);
        $close = Carbon::parse($date . ' ' . $closeTime);

        if ($start->lt($open)) {
            $start = $open->copy();
        }
        if ($start->gte($close)) {
            $start = $close->copy()->subMinutes(15);
        }

        $slots = [];
        for ($i = 0; $i < $columns; $i++) {
            $slot = $start->copy()->addMinutes($i * 15);
            if ($slot->gte($close)) {
                break;
            }
            $slots[] = $slot->format('H:i');
        }

        return $slots;
    }
}
