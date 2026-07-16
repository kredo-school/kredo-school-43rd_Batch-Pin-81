<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $notifications = $user
            ->notifications()
            ->latest()
            ->paginate(20);

        return view('restaurants.notifications', compact('notifications'));
    }

    public function read(DatabaseNotification $notification)
    {
        abort_if($notification->notifiable_id !== Auth::id(), 403);

        $notification->markAsRead();

        $redirectUrl = match ($notification->data['type'] ?? '') {
            'contact_reply', 'restaurant_contact_reply' => route('restaurant.settings.contact.index'),
            default => $notification->data['url'] ?? route('restaurant.notifications'),
        };

        return redirect()->to($redirectUrl);
    }
}
