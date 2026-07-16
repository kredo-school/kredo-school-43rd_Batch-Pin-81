<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Restaurant;
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

        $notifications->getCollection()->transform(function ($notification) {
            $notificationType = $notification->data['type'] ?? 'restaurant';

            if ($notificationType === 'restaurant') {

                $restaurant = Restaurant::with('user')
                    ->find($notification->data['restaurant_id']);

                $notification->restaurant = $restaurant;
                $notification->restaurant_status = $restaurant?->status ?? 'pending';
            }

            if ($notificationType === 'contact') {
                $contact = Contact::with(['user'])
                    ->find($notification->data['contact_id'] ?? null);

                $notification->contact = $contact;
            }

            if ($notificationType === 'restaurant_contact') {
                $contact = Contact::with(['user', 'restaurant'])
                    ->find($notification->data['contact_id'] ?? null);

                $notification->contact = $contact;
            }

            return $notification;
        });


        return view('admin.notifications.index', compact('notifications'));
    }

    public function read(DatabaseNotification $notification)
    {
        abort_if($notification->notifiable_id !== Auth::id(), 403);

        $notification->markAsRead();

        return response()->noContent();
    }
}
