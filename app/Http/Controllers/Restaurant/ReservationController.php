<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReservationController extends Controller
{

    public function dashboard()
    {
        return view('restaurants.index');
    }
    
    public function index() 
    {
       return view('restaurants.reservations');
    }
}
