<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function registerRestaurant()
    {
        return view('auth.restaurant-register');
    }

    public function settings()
    {
        return view('customer.settings');
    }
}
