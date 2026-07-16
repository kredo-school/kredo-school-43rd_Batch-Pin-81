<?php

namespace App\Notifications;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class RestaurantContactReplyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Contact $contact,
        public Contact $reply
    ) {}

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
            'type' => 'restaurant_contact_reply',
            'title' => 'Reply from Admin',
            'message' => 'The administrator has replied to your inquiry.',
            'contact_id' => $this->contact->id,
            'reply_id' => $this->reply->id,
            'url' => route('restaurant.settings.contact.index'),
            'button_text' => 'View Reply',
        ];
    }

    /**
     * Array representation.
     */
    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
