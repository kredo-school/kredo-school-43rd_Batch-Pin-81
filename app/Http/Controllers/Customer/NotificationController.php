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

        $redirectUrl = $notification->data['url'] ?? match ($notification->data['type'] ?? '') {
            'reservation' => data_get($notification->data, 'reservation_id')
                ? route('booking.confirmation', $notification->data['reservation_id'])
                : route('customer.notifications'),
            'contact_reply' => route('contact.index'),
            default => route('customer.notifications'),
        };

        return redirect()->to($redirectUrl);
    }
}
