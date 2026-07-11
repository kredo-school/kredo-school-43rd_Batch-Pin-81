<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
         $notifications = auth()->user()
        ->notifications()
        ->latest()
        ->paginate(20);

        return view('restaurants.notifications', compact('notifications'));
    }
}
