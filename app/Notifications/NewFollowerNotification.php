<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewFollowerNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public User $follower) {}

    /**
     * Delivery channels.
     */
    public function via(object $notifiable): array
    {
        return [
            'database',
            // 'broadcast'
        ];
    }

    /**
     * Database notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'new_follower',
            'title' => 'New Follower',
            'message' => "{$this->follower->name} started following you.",
            'follower_id' => $this->follower->id,
            'follower_name' => $this->follower->name,
            'profile_photo' => $this->follower->profile_photo,
            'url' => route('profile.show', $this->follower),
            'button_text' => 'View Profile',
        ];
    }

    /**
     * Broadcast notification.
     */
    // public function toBroadcast(object $notifiable): BroadcastMessage
    // {
    //     return new BroadcastMessage([
    //         'type' => 'new_follower',

    //         'title' => 'New Follower',

    //         'message' => "{$this->follower->name} started following you.",

    //         'follower_id' => $this->follower->id,

    //         'follower_name' => $this->follower->name,

    //         'profile_photo' => $this->follower->profile_photo,

    //         'url' => route('profile.show', $this->follower),

    //         'button_text' => 'View Profile',
    //     ]);
    // }
}
