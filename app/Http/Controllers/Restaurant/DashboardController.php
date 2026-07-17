<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Restaurant;
use App\Models\Table;
use App\Services\ReservationAvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DashboardController extends Controller
{
    public function __construct(private ReservationAvailabilityService $availability) {}

    public function index(Request $request)
    {
        $restaurant = $this->currentRestaurant();

        if (!$restaurant) {
            return view('restaurants.dashboard.index', [
                'restaurant' => null,
                'tables' => collect(),
                'date' => $request->input('date', now()->format('Y-m-d')),
                'activeReservations' => collect(),
                'cancelledReservations' => collect(),
                'timeSlots' => [],
                'displayStartTime' => '17:00',
                'timelineOpen' => null,
                'timelineClose' => null,
                'isClosed' => true,
                'durationLabel' => '2 hours',
                'immediateReservation' => null,
                'focusReservationId' => null,
            ]);
        }

        $date = $request->input('date', now()->format('Y-m-d'));
        $focusReservationId = $request->integer('focus');
        $bounds = $this->availability->timelineBounds($restaurant, $date);
        $displayStartTime = $request->input('start_time', $bounds['open'] ?? '17:00');
        $timeSlots = $this->availability->generateTimelineSlots(
            $date,
            $displayStartTime,
            $bounds['open'],
            $bounds['close']
        );

        $displayStartTime = $timeSlots[0] ?? $displayStartTime;

        $tables = Table::where('restaurant_id', $restaurant->id)
            ->orderBy('id')
            ->with([
                'reservations' => function ($query) use ($date) {
                    $query->whereDate('reservation_date', $date)
                        ->whereIn('status', ['pending', 'confirmed', 'completed'])
                        ->with('user')
                        ->orderBy('reservation_time');
                },
            ])
            ->get();

        $activeReservations = Reservation::where('restaurant_id', $restaurant->id)
            ->whereDate('reservation_date', $date)
            ->whereIn('status', ['pending', 'confirmed', 'completed'])
            ->with(['user', 'table'])
            ->orderBy('reservation_time')
            ->get();

        $cancelledReservations = Reservation::where('restaurant_id', $restaurant->id)
            ->whereDate('reservation_date', $date)
            ->where('status', 'cancelled')
            ->with(['user', 'table'])
            ->orderBy('reservation_time')
            ->get();

        $now = now();
        $oneHourLater = $now->copy()->addHour();

        $immediateReservation = Reservation::where('restaurant_id', $restaurant->id)
            ->where('status', 'pending')
            ->where('booking_source', 'online')
            ->whereBetween('reservation_date', [
                $now->toDateString(),
                $oneHourLater->toDateString(),
            ])
            ->with(['user', 'table'])
            ->latest('created_at')
            ->get()
            ->first(function (Reservation $reservation) use ($now, $oneHourLater) {
                $reservationDateTime = Carbon::parse(
                    Carbon::parse($reservation->reservation_date)->format('Y-m-d')
                        . ' '
                        . Carbon::parse($reservation->reservation_time)->format('H:i:s')
                );

                return $reservationDateTime->betweenIncluded($now, $oneHourLater);
            });

        return view('restaurants.dashboard.index', [
            'restaurant' => $restaurant,
            'tables' => $tables,
            'date' => $date,
            'activeReservations' => $activeReservations,
            'cancelledReservations' => $cancelledReservations,
            'timeSlots' => $timeSlots,
            'displayStartTime' => $displayStartTime,
            'timelineOpen' => $bounds['open'],
            'timelineClose' => $bounds['close'],
            'isClosed' => $bounds['closed'],
            'durationLabel' => $this->availability->durationLabel($restaurant),
            'immediateReservation' => $immediateReservation,
            'focusReservationId' => $focusReservationId ?: null,
        ]);
    }

    public function manualAvailability(Request $request)
    {
        $restaurant = $this->currentRestaurant();
        abort_if(!$restaurant, 404);

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'time' => ['required', 'date_format:H:i'],
            'guests' => ['required', 'integer', 'min:1', 'max:50'],
        ]);

        $start = Carbon::parse($validated['date'] . ' ' . $validated['time']);

        if ($start->lt(now()->copy()->subMinute())) {
            return response()->json(['tables' => [], 'message' => 'Past times cannot be selected.']);
        }

        if (!$this->availability->isWithinOperatingHours(
            $restaurant,
            $validated['date'],
            $validated['time']
        )) {
            return response()->json(['tables' => [], 'message' => 'The selected time is outside operating hours.']);
        }

        $tables = Table::query()
            ->where('restaurant_id', $restaurant->id)
            ->where('is_active', true)
            ->where('capacity', '>=', $validated['guests'])
            ->orderBy('capacity')
            ->orderBy('id')
            ->get()
            ->filter(fn(Table $table) => !$this->availability->tableHasConflict(
                $table,
                $restaurant,
                $validated['date'],
                $validated['time'],
                (int) $validated['guests']
            ))
            ->map(fn(Table $table) => [
                'id' => $table->id,
                'name' => $table->table_name,
                'capacity' => $table->capacity,
            ])
            ->values();

        return response()->json([
            'tables' => $tables,
            'message' => $tables->isEmpty() ? 'No available tables match the selected conditions.' : '',
        ]);
    }

    public function storeManualReservation(Request $request)
    {
        $restaurant = $this->currentRestaurant();
        abort_if(!$restaurant, 404);

        $validated = $request->validate([
            'booking_source' => ['required', 'in:phone,walk_in'],
            'guest_name' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:50'],
            'num_of_people' => ['required', 'integer', 'min:1', 'max:50'],
            'reservation_date' => ['required', 'date'],
            'reservation_time' => ['required', 'date_format:H:i'],
            'table_id' => ['required', 'integer', 'exists:tables,id'],
        ]);

        $start = Carbon::parse($validated['reservation_date'] . ' ' . $validated['reservation_time']);
        if ($start->lt(now()->copy()->subMinute())) {
            throw ValidationException::withMessages([
                'reservation_time' => 'Past times cannot be selected.',
            ]);
        }

        if (((int) Carbon::parse($validated['reservation_time'])->minute % 15) !== 0) {
            throw ValidationException::withMessages([
                'reservation_time' => 'Please select a 15-minute interval.',
            ]);
        }

        if (!$this->availability->isWithinOperatingHours(
            $restaurant,
            $validated['reservation_date'],
            $validated['reservation_time']
        )) {
            throw ValidationException::withMessages([
                'reservation_time' => 'The selected time is outside operating hours.',
            ]);
        }

        $reservation = DB::transaction(function () use ($validated, $restaurant) {
            $table = Table::query()
                ->whereKey($validated['table_id'])
                ->where('restaurant_id', $restaurant->id)
                ->where('is_active', true)
                ->lockForUpdate()
                ->firstOrFail();

            if ((int) $table->capacity < (int) $validated['num_of_people']) {
                throw ValidationException::withMessages([
                    'table_id' => 'The selected table does not have enough seats.',
                ]);
            }

            if ($this->availability->tableHasConflict(
                $table,
                $restaurant,
                $validated['reservation_date'],
                $validated['reservation_time'],
                (int) $validated['num_of_people']
            )) {
                throw ValidationException::withMessages([
                    'table_id' => 'That table is no longer available for the selected time.',
                ]);
            }

            return Reservation::create([
                'user_id' => null,
                'restaurant_id' => $restaurant->id,
                'table_id' => $table->id,
                'guest_name' => $validated['guest_name'] ?: null,
                'phone_number' => $validated['phone_number'] ?: null,
                'booking_source' => $validated['booking_source'],
                'num_of_people' => $validated['num_of_people'],
                'reservation_date' => $validated['reservation_date'],
                'reservation_time' => $validated['reservation_time'],
                'end_time' => $this->availability->reservationEndTimeForDb(
                    $restaurant,
                    $validated['reservation_date'],
                    $validated['reservation_time']
                ),
                'status' => 'confirmed',
            ]);
        });

        return redirect()->route('restaurant.dashboard', [
            'date' => $validated['reservation_date'],
            'start_time' => $validated['reservation_time'],
            'focus' => $reservation->id,
        ])->with('success', 'Manual reservation added successfully.');
    }

    public function storeTable(Request $request)
    {
        $restaurant = $this->currentRestaurant();
        abort_if(!$restaurant, 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'integer', 'min:1', 'max:50'],
        ]);

        Table::create([
            'restaurant_id' => $restaurant->id,
            'table_name' => $validated['name'],
            'capacity' => $validated['capacity'],
            'is_active' => true,
        ]);

        return redirect()->route('restaurant.dashboard', $this->dateQuery($request))
            ->with('success', 'Table added successfully.');
    }

    public function updateTable(Request $request, Table $table)
    {
        $restaurant = $this->currentRestaurant();
        abort_if(!$restaurant || $table->restaurant_id !== $restaurant->id, 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'integer', 'min:1', 'max:50'],
            'status' => ['nullable', 'in:enable,disable'],
        ]);

        $table->update([
            'table_name' => $validated['name'],
            'capacity' => $validated['capacity'],
            'is_active' => ($validated['status'] ?? 'enable') === 'enable',
        ]);

        return redirect()->route('restaurant.dashboard', $this->dateQuery($request))
            ->with('success', 'Table updated successfully.');
    }

    public function destroyTable(Request $request, Table $table)
    {
        $restaurant = $this->currentRestaurant();
        abort_if(!$restaurant || $table->restaurant_id !== $restaurant->id, 404);

        $hasFutureReservation = $table->reservations()
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($hasFutureReservation) {
            return redirect()->route('restaurant.dashboard', $this->dateQuery($request))
                ->with('error', 'This table has active reservations and cannot be deleted.');
        }

        $table->delete();

        return redirect()->route('restaurant.dashboard', $this->dateQuery($request))
            ->with('success', 'Table deleted successfully.');
    }

    private function currentRestaurant(): ?Restaurant
    {
        if (Auth::check()) {
            $restaurant = Restaurant::where('user_id', Auth::id())->first();
            if ($restaurant) {
                return $restaurant;
            }
        }

        return Restaurant::find(1) ?? Restaurant::first();
    }

    private function dateQuery(Request $request): array
    {
        return array_filter([
            'date' => $request->input('date'),
            'start_time' => $request->input('start_time'),
        ]);
    }
}
