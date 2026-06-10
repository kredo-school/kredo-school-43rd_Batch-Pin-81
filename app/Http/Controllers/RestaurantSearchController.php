<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RestaurantSearchController extends Controller
{
    public function register()
    {
        return view('auth.restaurant-register');
    }

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
}
