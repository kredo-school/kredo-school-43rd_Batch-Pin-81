<?php

namespace App\Notifications;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactMessageReceived extends Notification implements ShouldQueue
{
    use Queueable;

    protected $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    /**
     * Notification channels.
     */
    public function via($notifiable): array
    {
        return [
            'mail',
            'database',
            // 'broadcast',
        ];
    }

    /**
     * Database notification.
     */
    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'contact',
            'contact_id' => $this->contact->id,
            'title' => $this->contact->title,
            'message' => $this->contact->message,
            'user_name' => $this->contact->user->name,
            'user_id' => $this->contact->user_id,
        ];
    }

    /**
     * Email notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Contact Message Received')
            ->greeting('Hello Admin,')
            ->line('A customer has submitted a new contact message.')
            ->line('Title: ' . $this->contact->title)
            ->line('From: ' . $this->contact->user->name)
            ->action(
                'View Message',
                route('admin.contacts.index', $this->contact)
            )
            ->line('Please review the message as soon as possible.');
    }

    /**
     * Broadcast notification.
     */
    // public function toBroadcast($notifiable): BroadcastMessage
    // {
    //     return new BroadcastMessage([
    //         'type' => 'contact',
    //         'contact_id' => $this->contact->id,
    //         'title' => $this->contact->title,
    //         'message' => $this->contact->message,
    //         'user_name' => $this->contact->user->name,
    //         'user_id' => $this->contact->user_id,
    //     ]);
    // }

    /**
     * Optional: Use the same data for database notifications.
     */
    public function toArray($notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
