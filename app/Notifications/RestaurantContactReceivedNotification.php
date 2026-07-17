<?php

namespace App\Notifications;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class RestaurantContactReceivedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Contact $contact) {}

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Store notification in database.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'restaurant_contact',

            'title' => 'Restaurant Contact',

            'message' => "{$this->contact->restaurant->name} has sent a new contact message.",

            'contact_id' => $this->contact->id,

            'restaurant_id' => $this->contact->restaurant_id,

            'restaurant_name' => $this->contact->restaurant->name,

            'url' => route('admin.contacts.index', $this->contact),

            'button_text' => 'View Message',
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
