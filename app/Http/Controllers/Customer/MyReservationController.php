<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Reservation;
use App\Notifications\CustomerRunningLateNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['restaurant.photos'])
            ->where('user_id', Auth::id())
            ->orderBy('reservation_date', 'asc')
            ->orderBy('reservation_time', 'asc')
            ->get();

        $upcomingReservations = $reservations
            ->filter(fn($reservation) => $reservation->reservation_date?->isToday() || $reservation->reservation_date?->isFuture())
            ->map(fn($reservation) => $this->formatReservationForView($reservation))
            ->values();

        $pastReservations = $reservations
            ->filter(fn($reservation) => $reservation->reservation_date?->isPast())
            ->sortByDesc('reservation_date')
            ->sortByDesc('reservation_time')
            ->map(fn($reservation) => $this->formatReservationForView($reservation))
            ->values();

        return view('customers.my_reservations.index', compact('upcomingReservations', 'pastReservations'));
    }

    private function formatReservationForView(Reservation $reservation): object
    {
        $restaurant = $reservation->restaurant;
        $firstPhoto = $restaurant?->photos?->first();

        return (object) [
            'id' => $reservation->id,
            'restaurant_id' => $reservation->restaurant_id,
            'restaurant_name' => $restaurant?->restaurant_name ?? 'Unknown Restaurant',
            'location' => collect([
                $restaurant?->prefecture,
                $restaurant?->city,
                $restaurant?->street_address_building,
            ])->filter()->implode(', '),
            'reservation_code' => $reservation->reservation_code,
            'date' => $reservation->reservation_date?->format('Y-m-d'),
            'time' => $reservation->reservation_time
                ? Carbon::parse($reservation->reservation_time)->format('H:i')
                : null,
            'guests' => $reservation->num_of_people,
            'restaurant_image' => $firstPhoto
                ? asset('storage/' . $firstPhoto->photo_path)
                : 'https://via.placeholder.com/80',
        ];
    }

    // Notify late
    public function notifyLate(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'late_minutes' => ['required', 'integer', 'in:10,15'],
        ]);

        $lateMinutes = (int) $validated['late_minutes'];

        // Send notification
        $restaurantOwner = $reservation->restaurant->user;

        $restaurantOwner->notify(
            new CustomerRunningLateNotification($reservation, $lateMinutes)
        );

        return redirect()
            ->back()
            ->with('success', 'The restaurant has been notified that you will be late by ' . $lateMinutes . ' minutes.');
    }

    // Cancel reservation
    public function destroy(/*Reservation $reservation*/)
    {
        // $reservation->status = 'cancelled';
        // $reservation->save();

        return redirect()
            ->back()
            ->with('success', 'Reservation cancelled successfully.');
    }
    public function search()
    {
        return view('my_reservations');
    }
}
