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
}
