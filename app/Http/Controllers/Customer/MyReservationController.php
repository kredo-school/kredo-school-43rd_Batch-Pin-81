<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Notifications\CustomerRunningLateNotification;

class MyReservationController extends Controller
{
    public function index()
    {
        // $upcomingReservations = Reservation::where('user_id', auth()->id())
        //     ->where('date', '>=', now()->toDateString())
        //     ->orderBy('date', 'asc')
        //     ->get();

        // Create a quick fake object for the view

        // $pastReservations = Reservation::where('user_id', auth()->id())
        //     ->where('date', '<', now()->toDateString())
        //     ->orderBy('date', 'desc')
        //     ->get();

        // return view('reservations.index', compact('upcomingReservations', 'pastReservations'));

        // Mock Upcoming Data
        $upcomingReservations = [
            (object) [
                'id' => 1,
                'restaurant_name' => 'Sushi Masaru',
                'location' => 'Ginza, Tokyo',
                'reservation_code' => 'RM2026051501',
                'date' => '2026-05-20',
                'time' => '19:00',
                'guests' => 2,
                'restaurant_image' => 'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?w=150'
            ],
            (object) [
                'id' => 2,
                'restaurant_name' => 'Yakitori Tori',
                'location' => 'Shinjuku, Tokyo',
                'reservation_code' => 'RM2026051802',
                'date' => '2026-05-25',
                'time' => '18:30',
                'guests' => 4,
                'restaurant_image' => 'https://images.unsplash.com/photo-1563245372-f21724e3856d?w=150'
            ]
        ];

        // Mock Past Data
        $pastReservations = [
            (object) [
                'id' => 3,
                'restaurant_id' => 101,
                'restaurant_name' => 'Ramen Ichiban',
                'location' => 'Shibuya, Tokyo',
                'date' => '2026-05-15',
                'time' => '12:00',
                'guests' => 2,
                'restaurant_image' => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=150'
            ]
        ];

        return view('customers.my_reservations.index', compact('upcomingReservations', 'pastReservations'));
    }

    // Notify late
    public function notifyLate(/*Reservation $reservation*/)
    {
        // Example: save that the customer notified the restaurant
        // $reservation->is_late_notice = true;
        // $reservation->save();

        // Send notification
        $restaurantOwner = $reservation->restaurant->user;

        $restaurantOwner->notify(
            new CustomerRunningLateNotification($reservation)
        );

        return redirect()
            ->back()
            ->with('success', 'The restaurant has been notified that you will be late.');
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
