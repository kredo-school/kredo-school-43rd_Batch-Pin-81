<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\User;
use App\Notifications\NewRestaurantApplication;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()
        ->notifications()
        ->latest()
        ->paginate(20);

        return view('customer.notifications', compact('notifications'));
    }
}
