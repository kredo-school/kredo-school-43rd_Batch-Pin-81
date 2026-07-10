<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{

    public function create(Request $request, Restaurant $restaurant)
    {
        $selectedDate = now()->format('Y-m-d');
        $selectedTime = $request->time;

        $availableSlots = $restaurant->availableSlots();

        return view('customers.restaurants.book', compact(
            'restaurant',
            'selectedDate',
            'selectedTime',
            'availableSlots'
        ));
    }

    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'restaurant_id' => ['required', 'exists:restaurants,id'],
            'reservation_date' => ['required', 'date'],
            'reservation_time' => ['required'],
            'num_of_people' => ['required', 'integer', 'min:1'],
        ]);

        do {
            $reservationCode = strtoupper(Str::random(10));
        } while (Reservation::where('reservation_code', $reservationCode)->exists());
        
        
        $reservation = Reservation::create([
            'user_id' => auth()->check() ? auth()->id() : null,
            'restaurant_id' => $validated['restaurant_id'],
            'reservation_date' => $validated['reservation_date'],
            'reservation_time' => $validated['reservation_time'],
            'num_of_people' => $validated['num_of_people'],
            'reservation_code' => $reservationCode,
        ]);

        return redirect()->route('booking.confirmation', ['reservation' => $reservation]);
    }

    public function confirmation(Reservation $reservation)
    {
        return view('customers.restaurants.booking_confirmation', compact('reservation'));
    }
}
