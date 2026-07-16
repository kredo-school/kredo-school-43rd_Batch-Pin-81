<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Notifications\DatabaseNotification;

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

    public function read(DatabaseNotification $notification)
    {
        abort_if($notification->notifiable_id !== auth()->id(), 403);

        $notification->markAsRead();

        $redirectUrl = match ($notification->data['type'] ?? '') {
            'reservation' => route('my_reservations'),
            'contact_reply' => route('contact.index'),
            'restaurant_application', 'restaurant_approved' => route('restaurant.dashboard'),
            default => $notification->data['url'] ?? route('customer.notifications'),
        };

        return redirect()->to($redirectUrl);
    }
}
