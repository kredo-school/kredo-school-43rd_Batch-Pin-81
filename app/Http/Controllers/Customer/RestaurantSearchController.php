<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;

class RestaurantSearchController extends Controller
{

    public function view()
    {
        return view('customers.restaurants.index');
    }

    public function show()
    {
        $photos = [
            asset('https://picsum.photos/400/250?random=6'),
            asset('https://picsum.photos/400/250?random=10'),
            asset('https://picsum.photos/400/250?random=9'),
            asset('https://picsum.photos/400/250?random=8'),
            asset('https://picsum.photos/400/250?random=7'),
        ];

        shuffle($photos);

        return view('customers.restaurants.show', compact('photos'));
    }

    public function displayBookingPage()
    {
        return view('customers.restaurants.book');
    }

    public function store(Request $request)
    {
        // Save booking to database

        return redirect()->route('booking.confirmation');
    }
}
