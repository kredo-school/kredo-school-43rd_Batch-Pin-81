<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

            $restaurant = Restaurant::with('user')->find($notification->data['restaurant_id']);

            $notification->restaurant = $restaurant;
            $notification->restaurant_status = $restaurant?->status ?? 'pending';

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
