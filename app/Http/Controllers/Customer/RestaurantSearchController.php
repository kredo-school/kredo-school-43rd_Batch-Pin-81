<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Reservation;

class RestaurantSearchController extends Controller
{
    public function view()
    {
        // $restaurants = Restaurant::all();
        $restaurants = Restaurant::with([
            'photos',
            'categories',
            'features'
        ])->withAvg('posts', 'rating')->get();


        return view('customers.restaurants.index', compact('restaurants'));
    }

    public function show(Restaurant $restaurant)
    {

        $restaurant->load([
            'photos',
            'categories',
            'features',
            'menus',
            'menus.photos',
            'posts.user',
            'posts.photos'
        ]);

        $restaurant->loadAvg('posts', 'rating')
            ->loadCount('posts');

        $availableSlots = $restaurant->availableSlots();

        return view('customers.restaurants.show', compact('restaurant', 'availableSlots'));
    }

    public function store(Request $request)
    {
        // Save booking to database

        return redirect()->route('booking.confirmation');
    }
}
