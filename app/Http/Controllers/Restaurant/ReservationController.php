<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Restaurant;
use App\Services\ReservationAvailabilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function __construct(
        private ReservationAvailabilityService $availability
    ) {
        // ここでは auth()->user() を使わない
        // php artisan route:list 実行時はログインユーザーがいないため
    }

    public function index(Request $request)
    {
        $restaurant = $this->currentRestaurant();

        $query = Reservation::where('restaurant_id', $restaurant->id)
            ->with(['user', 'table'])
            ->orderBy('reservation_date', 'asc')
            ->orderBy('reservation_time', 'asc');

        $selectedDate = $request->input('date');

        if ($selectedDate) {
            $query->whereDate('reservation_date', $selectedDate);
        }

        if ($request->filled('search_id')) {
            $cleanId = preg_replace('/[^0-9]/', '', $request->input('search_id'));

            if (!empty($cleanId)) {
                $query->where('id', $cleanId);
            }
        }

        $reservations = $query->get();

        return view('restaurants.reservations.index', [
            'reservations' => $reservations,
            'pendingReservations' => $reservations->where('status', 'pending'),
            'confirmedReservations' => $reservations->where('status', 'confirmed'),
            'completedReservations' => $reservations->where('status', 'completed'),
            'cancelledReservations' => $reservations->where('status', 'cancelled'),
            'selectedDate' => $selectedDate,
        ]);
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        $restaurant = $this->currentRestaurant();

        if ($reservation->restaurant_id !== $restaurant->id) {
            abort(404);
        }

        $validated = $request->validate([
            'status' => ['required', 'in:confirmed,completed,cancelled'],
        ]);

        if ($validated['status'] === 'cancelled') {
            $reservation->cancelled_by = 'restaurant';
        } else {
            $reservation->cancelled_by = null;
        }

        $reservation->status = $validated['status'];
        $reservation->save();

        return redirect()
            ->back()
            ->with('success', 'Reservation status updated successfully.');
    }

    private function currentRestaurant(): Restaurant
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Please login first.');
        }

        $restaurant = Restaurant::where('user_id', $user->id)->first();

        if ($restaurant) {
            return $restaurant;
        }

        // Keep the behavior aligned with the restaurant dashboard for demo/test accounts.
        $fallbackRestaurant = Restaurant::first();

        if (!$fallbackRestaurant) {
            abort(403, 'Restaurant account is not found.');
        }

        return $fallbackRestaurant;
    }
}
