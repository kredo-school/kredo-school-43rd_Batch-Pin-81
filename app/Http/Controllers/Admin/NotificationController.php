<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Restaurant;



class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()
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
                $contact = Contact::with('user')
                    ->find($notification->data['contact_id'] ?? null);

                $notification->contact = $contact;
            }

            return $notification;
        });
            

        return view('admin.notifications.index', compact('notifications'));
    }

    // private function markNotificationAsRead(?string $notificationId): void
    // {
    //     if (!$notificationId) {
    //         return;
    //     }

    //     $notification = auth()
    //         ->user()
    //         ->notifications()
    //         ->find($notificationId);

    //     if ($notification) {
    //         $notification->markAsRead();
    //     }
    // }
}
