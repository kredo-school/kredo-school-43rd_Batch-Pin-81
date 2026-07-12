<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Restaurant;
use App\Models\User;
use App\Notifications\ReservationSubmittedNotification;
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'requests' => ['nullable', 'string'],
        ]);

        $guestNameParts = preg_split('/\s+/', trim($validated['name']), 2);
        $firstName = $guestNameParts[0] ?? 'Guest';
        $lastName = $guestNameParts[1] ?? '';

        $user = auth()->user() ?? User::firstOrCreate(
            ['email' => $validated['email']],
            [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'password' => bcrypt(Str::random(32)),
                'role_id' => User::ROLE_USER,
            ]
        );

        $reservation = Reservation::create([
            'user_id' => $user->id,
            'restaurant_id' => $validated['restaurant_id'],
            'reservation_date' => $validated['reservation_date'],
            'reservation_time' => $validated['reservation_time'],
            'num_of_people' => $validated['num_of_people'],
        ]);

         // 👇 Send the notification here
        {        
            $restaurantOwner = $reservation->restaurant->user;

            if ($restaurantOwner) {
                $restaurantOwner->notify(
                    new ReservationSubmittedNotification($reservation)
                );
            }
        }

        return redirect()->route('booking.confirmation', ['reservation' => $reservation]);
    }

    public function confirmation(Reservation $reservation)
    {

        return view('customers.restaurants.booking_confirmation', compact('reservation'));
    }
}
