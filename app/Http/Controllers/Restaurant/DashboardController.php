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

class DashboardController extends Controller
{
    public function __construct(private ReservationAvailabilityService $availability)
    {
    }

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
            ]);
        }

        $date = $request->input('date', now()->format('Y-m-d'));
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
        $immediateReservation = null;

        if ($date === $now->toDateString()) {
            $immediateReservation = Reservation::where('restaurant_id', $restaurant->id)
                ->whereDate('reservation_date', $date)
                ->where('status', 'pending')
                ->whereTime('reservation_time', '>=', $now->format('H:i:s'))
                ->whereTime('reservation_time', '<=', $oneHourLater->format('H:i:s'))
                ->with(['user', 'table'])
                ->orderBy('reservation_time')
                ->first();
        }

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
        ]);
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
