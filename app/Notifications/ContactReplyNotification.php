<?php

namespace App\Notifications;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ContactReplyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $contact;
    public $reply;

    public function __construct(Contact $contact, string $reply)
    {
        $this->contact = $contact;
        $this->reply = $reply;
    }


    public function via($notifiable)
    {
        return [
            'database',
            'mail'
            // 'broadcast'
        ];
    }


    public function toDatabase($notifiable)
    {
        return [
            'type' => 'contact_reply',
            'title' => 'Reply from Support',
            'message' => 'Our support team has replied to your inquiry.',
            'reply' => $this->reply,
            'contact_id' => $this->contact->id,
            'url' => route('contact.index'),
            'button_text' => 'View Reply',
        ];
    }


    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Support replied to your message')
            ->line('Admin replied to your contact message.')
            ->line($this->reply);
    }


    // public function toBroadcast($notifiable)
    // {
    //     return new BroadcastMessage($this->toDatabase($notifiable));
    // }


    public function toArray($notifiable)
    {
        return [
            'type' => 'contact_reply',
            'title' => 'New reply from support',
            'message' => $this->reply,
            'reply' => $this->reply,
            'contact_id' => $this->contact->id,
            'url' => route('contact.index'),
            'button_text' => 'View Reply',
        ];
    }
}
