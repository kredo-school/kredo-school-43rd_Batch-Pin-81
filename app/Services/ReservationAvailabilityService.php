<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\Restaurant;
use App\Models\Table;
use Carbon\Carbon;

class ReservationAvailabilityService
{
    public const CLEANING_MINUTES = 15;
    public const SLOT_INTERVAL_MINUTES = 15;

    /**
     * Restaurant側で設定した滞在時間（分）。
     */
    public function stayMinutes(Restaurant $restaurant): int
    {
        return max(
            (int) ($restaurant->stay_duration ?? 120),
            self::SLOT_INTERVAL_MINUTES
        );
    }

    /**
     * 既存のRestaurant画面との互換性を保つためのメソッド。
     */
    public function slotMinutes(Restaurant $restaurant): int
    {
        return $this->stayMinutes($restaurant);
    }

    /**
     * 滞在時間＋清掃15分。
     */
    public function blockMinutes(Restaurant $restaurant): int
    {
        return $this->stayMinutes($restaurant) + self::CLEANING_MINUTES;
    }

    public function durationLabel(Restaurant $restaurant): string
    {
        $minutes = $this->stayMinutes($restaurant);
        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;
        $parts = [];

        if ($hours > 0) {
            $parts[] = $hours . ' ' . ($hours === 1 ? 'hour' : 'hours');
        }

        if ($remainingMinutes > 0) {
            $parts[] = $remainingMinutes . ' minutes';
        }

        return implode(' ', $parts);
    }

    public function reservationEnd(
        Reservation $reservation,
        Restaurant $restaurant,
        bool $includeCleaning = true
    ): Carbon {
        $start = Carbon::parse(
            $reservation->reservation_date->format('Y-m-d')
                . ' '
                . $reservation->reservation_time
        );

        $minutes = $this->stayMinutes($restaurant)
            + ($includeCleaning ? self::CLEANING_MINUTES : 0);

        return $start->copy()->addMinutes($minutes);
    }

    public function reservationEndTimeForDb(
        Restaurant $restaurant,
        string $date,
        string $time
    ): string {
        return Carbon::parse($date . ' ' . $time)
            ->addMinutes($this->stayMinutes($restaurant))
            ->format('H:i:s');
    }

    public function getDayHours(Restaurant $restaurant, string $date): array
    {
        $dayName = Carbon::parse($date)->format('l');
        $hours = $this->normalizeOperatingHours(
            $restaurant->operating_hours ?? []
        );

        return $hours[$dayName] ?? [
            'closed' => true,
            'shifts' => [],
        ];
    }

    public function normalizeOperatingHours(mixed $rawHours): array
    {
        if (is_string($rawHours)) {
            $decoded = json_decode($rawHours, true);
            $rawHours = is_array($decoded) ? $decoded : [];
        }

        if (!is_array($rawHours)) {
            $rawHours = [];
        }

        $normalized = [];

        foreach (
            [
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
                'Saturday',
                'Sunday',
            ] as $day
        ) {
            $dayValue = $rawHours[$day]
                ?? $rawHours[strtolower($day)]
                ?? [];

            $closed = false;
            $shifts = [];

            if (is_array($dayValue)) {
                $closed = filter_var(
                    $dayValue['closed'] ?? false,
                    FILTER_VALIDATE_BOOLEAN
                );

                $rawShifts = $dayValue['shifts'] ?? $dayValue;

                if (
                    array_key_exists('open', $dayValue)
                    || array_key_exists('close', $dayValue)
                ) {
                    $rawShifts = [$dayValue];
                }

                foreach ($rawShifts as $key => $shift) {
                    if ($key === 'closed' || !is_array($shift)) {
                        continue;
                    }

                    $open = isset($shift['open'])
                        ? substr((string) $shift['open'], 0, 5)
                        : null;

                    $close = isset($shift['close'])
                        ? substr((string) $shift['close'], 0, 5)
                        : null;

                    if ($open && $close && $open < $close) {
                        $shifts[] = [
                            'open' => $open,
                            'close' => $close,
                        ];
                    }
                }
            }

            $normalized[$day] = [
                'closed' => $closed || empty($shifts),
                'shifts' => $closed ? [] : $shifts,
            ];
        }

        return $normalized;
    }

    /**
     * Restaurantプロフィール更新処理で使用。
     */
    public function buildOperatingHoursFromRequest(array $hours): array
    {
        $normalized = [];

        foreach (
            [
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
                'Saturday',
                'Sunday',
            ] as $day
        ) {
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
                        'open' => substr((string) $open, 0, 5),
                        'close' => substr((string) $close, 0, 5),
                    ];
                }
            }

            $normalized[$day] = [
                'closed' => $closed || empty($shifts),
                'shifts' => $closed ? [] : $shifts,
            ];
        }

        return $normalized;
    }

    /**
     * Customer側に表示する予約可能時間。
     */
    public function generateAvailableStartTimes(
        Restaurant $restaurant,
        string $date,
        int $partySize
    ): array {
        if (
            $partySize < 1
            || Carbon::parse($date)->startOfDay()->lt(now()->startOfDay())
        ) {
            return [];
        }

        $dayHours = $this->getDayHours($restaurant, $date);

        if ($dayHours['closed'] || empty($dayHours['shifts'])) {
            return [];
        }

        $times = [];
        $blockMinutes = $this->blockMinutes($restaurant);
        $earliestToday = $this->earliestBookableTime($date);

        foreach ($dayHours['shifts'] as $shift) {
            $cursor = Carbon::parse($date . ' ' . $shift['open'])
                ->seconds(0);

            $close = Carbon::parse($date . ' ' . $shift['close'])
                ->seconds(0);

            while (
                $cursor->copy()->addMinutes($blockMinutes)->lte($close)
            ) {
                $time = $cursor->format('H:i');

                if (
                    (!$earliestToday || $cursor->gte($earliestToday))
                    && $this->findAvailableTable(
                        $restaurant,
                        $date,
                        $time,
                        $partySize
                    )
                ) {
                    $times[] = $time;
                }

                $cursor->addMinutes(self::SLOT_INTERVAL_MINUTES);
            }
        }

        return array_values(array_unique($times));
    }

    /**
     * 既存コードとの互換性を保つためのメソッド。
     */
    public function generateStartTimes(
        Restaurant $restaurant,
        string $date,
        ?int $partySize = null
    ): array {
        if ($partySize !== null) {
            return $this->generateAvailableStartTimes(
                $restaurant,
                $date,
                $partySize
            );
        }

        $dayHours = $this->getDayHours($restaurant, $date);

        if ($dayHours['closed'] || empty($dayHours['shifts'])) {
            return [];
        }

        $times = [];
        $blockMinutes = $this->blockMinutes($restaurant);

        foreach ($dayHours['shifts'] as $shift) {
            $cursor = Carbon::parse($date . ' ' . $shift['open']);
            $close = Carbon::parse($date . ' ' . $shift['close']);

            while (
                $cursor->copy()->addMinutes($blockMinutes)->lte($close)
            ) {
                $times[] = $cursor->format('H:i');
                $cursor->addMinutes(self::SLOT_INTERVAL_MINUTES);
            }
        }

        return array_values(array_unique($times));
    }

    public function earliestBookableTime(string $date): ?Carbon
    {
        $targetDate = Carbon::parse($date)->startOfDay();

        if (!$targetDate->isToday()) {
            return null;
        }

        $now = now()->copy()->seconds(0);
        $remainder = $now->minute % self::SLOT_INTERVAL_MINUTES;

        if ($remainder !== 0) {
            $now->addMinutes(
                self::SLOT_INTERVAL_MINUTES - $remainder
            );
        }

        return $now;
    }

    /**
     * 予約人数以上のcapacityを持つ空きテーブルのうち、
     * capacityが最小のものを優先する。
     */
    public function findAvailableTable(
        Restaurant $restaurant,
        string $date,
        string $time,
        int $partySize,
        ?int $ignoreReservationId = null,
        bool $lockForUpdate = false
    ): ?Table {
        $query = Table::query()
            ->where('restaurant_id', $restaurant->id)
            ->where('is_active', true)
            ->where('capacity', '>=', $partySize)
            ->orderBy('capacity')
            ->orderBy('id');

        if ($lockForUpdate) {
            $query->lockForUpdate();
        }

        return $query->get()->first(
            function (Table $table) use (
                $restaurant,
                $date,
                $time,
                $partySize,
                $ignoreReservationId
            ) {
                return !$this->tableHasConflict(
                    $table,
                    $restaurant,
                    $date,
                    $time,
                    $partySize,
                    $ignoreReservationId
                );
            }
        );
    }

    public function tableHasConflict(
        Table $table,
        Restaurant $restaurant,
        string $date,
        string $time,
        int $partySize,
        ?int $ignoreReservationId = null
    ): bool {
        if (!$table->is_active || $partySize > $table->capacity) {
            return true;
        }

        $newStart = Carbon::parse($date . ' ' . $time);
        $newEnd = $newStart->copy()
            ->addMinutes($this->blockMinutes($restaurant));

        $query = Reservation::query()
            ->where('table_id', $table->id)
            ->where('restaurant_id', $restaurant->id)
            ->whereDate('reservation_date', $date)
            ->whereIn(
                'status',
                ['pending', 'confirmed', 'completed']
            );

        if ($ignoreReservationId !== null) {
            $query->whereKeyNot($ignoreReservationId);
        }

        return $query->get()->contains(
            function (Reservation $reservation) use (
                $restaurant,
                $newStart,
                $newEnd
            ) {
                $existingStart = Carbon::parse(
                    $reservation->reservation_date->format('Y-m-d')
                        . ' '
                        . $reservation->reservation_time
                );

                $existingEnd = $existingStart->copy()
                    ->addMinutes($this->blockMinutes($restaurant));

                return $newStart->lt($existingEnd)
                    && $newEnd->gt($existingStart);
            }
        );
    }

    public function isWithinOperatingHours(
        Restaurant $restaurant,
        string $date,
        string $time
    ): bool {
        $dayHours = $this->getDayHours($restaurant, $date);

        if ($dayHours['closed']) {
            return false;
        }

        $start = Carbon::parse($date . ' ' . $time);
        $end = $start->copy()
            ->addMinutes($this->blockMinutes($restaurant));

        foreach ($dayHours['shifts'] as $shift) {
            $open = Carbon::parse($date . ' ' . $shift['open']);
            $close = Carbon::parse($date . ' ' . $shift['close']);

            if ($start->gte($open) && $end->lte($close)) {
                return true;
            }
        }

        return false;
    }

    public function statusForStart(string $date, string $time): string
    {
        $start = Carbon::parse($date . ' ' . $time);
        $minutesUntilStart = now()->diffInMinutes($start, false);

        return $minutesUntilStart <= 60
            ? 'pending'
            : 'confirmed';
    }

    /**
     * Restaurant Dashboardで使用。
     */
    public function timelineBounds(
        Restaurant $restaurant,
        string $date
    ): array {
        $dayHours = $this->getDayHours($restaurant, $date);

        if (
            $dayHours['closed']
            || empty($dayHours['shifts'])
        ) {
            return [
                'open' => null,
                'close' => null,
                'closed' => true,
            ];
        }

        $opens = collect($dayHours['shifts'])
            ->pluck('open')
            ->filter()
            ->sort()
            ->values();

        $closes = collect($dayHours['shifts'])
            ->pluck('close')
            ->filter()
            ->sort()
            ->values();

        return [
            'open' => $opens->first(),
            'close' => $closes->last(),
            'closed' => false,
        ];
    }

    /**
     * Restaurant Dashboardで使用。
     */
    public function generateTimelineSlots(
        string $date,
        ?string $displayStartTime,
        ?string $openTime,
        ?string $closeTime,
        int $columns = 8
    ): array {
        if (!$openTime || !$closeTime) {
            return [];
        }

        $start = Carbon::parse(
            $date . ' ' . ($displayStartTime ?: $openTime)
        );

        $open = Carbon::parse($date . ' ' . $openTime);
        $close = Carbon::parse($date . ' ' . $closeTime);

        if ($start->lt($open)) {
            $start = $open->copy();
        }

        if ($start->gte($close)) {
            $start = $close->copy()
                ->subMinutes(self::SLOT_INTERVAL_MINUTES);
        }

        $slots = [];

        for ($i = 0; $i < $columns; $i++) {
            $slot = $start->copy()->addMinutes(
                $i * self::SLOT_INTERVAL_MINUTES
            );

            if ($slot->gte($close)) {
                break;
            }

            $slots[] = $slot->format('H:i');
        }

        return $slots;
    }
}
