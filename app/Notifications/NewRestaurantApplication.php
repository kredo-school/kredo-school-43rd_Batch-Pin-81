<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use App\Models\Restaurant;

class NewRestaurantApplication extends Notification
{
    protected Restaurant $restaurant;

    public function __construct(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [
            'mail',
            'database',
            // 'broadcast'
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Restaurant Application')
            ->line('A new restaurant application has been submitted.')
            ->line('Restaurant: ' . $this->restaurant->restaurant_name)
            ->action('Review Application', url('/admin/restaurants'))
            ->line('Please review the application.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'restaurant',
            'title' => 'New Restaurant application',
            'restaurant_id' => $this->restaurant->id,
            'restaurant_name' => $this->restaurant->restaurant_name,
            'restaurant_status' => $this->restaurant->status,
            'message' => $this->restaurant->restaurant_name . ' has submitted a restaurant application.',
            'url' => route('admin.restaurants'),
        ];
    }

    // public function toBroadcast(object $notifiable): BroadcastMessage
    // {
    //     return new BroadcastMessage([
    //         'type' => 'restaurant',
    //         'restaurant_id' => $this->restaurant->id,
    //         'restaurant_name' => $this->restaurant->restaurant_name,
    //         'restaurant_status' => $this->restaurant->status,
    //         'message' => $this->restaurant->restaurant_name . ' has submitted a restaurant application.',
    //     ]);
    // }
}
