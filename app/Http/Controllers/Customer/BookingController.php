<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use Carbon\Carbon;

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
}
