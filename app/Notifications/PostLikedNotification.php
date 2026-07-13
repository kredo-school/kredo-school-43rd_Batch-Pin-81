<?php

namespace App\Notifications;

use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostLikedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public User $liker,
        public Post $post
    ) {}

    public function via(object $notifiable): array
    {
        return [
            'database',
            // 'broadcast'
        ];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'post_liked',
            'title' => 'Someone liked your post',
            'message' => "{$this->liker->name} liked your post.",
            'liker_id' => $this->liker->id,
            'liker_name' => $this->liker->name,
            'profile_photo' => $this->liker->profile_photo,
            'post_id' => $this->post->id,
            'url' => route('posts.show', $this->post),
            'button_text' => 'View Post',
        ];
    }

    // public function toBroadcast(object $notifiable): BroadcastMessage
    // {
    //     return new BroadcastMessage([
    //         'type' => 'post_liked',

    //         'title' => 'Someone liked your post',

    //         'message' => "{$this->liker->name} liked your post.",

    //         'liker_id' => $this->liker->id,

    //         'liker_name' => $this->liker->name,

    //         'profile_photo' => $this->liker->profile_photo,

    //         'post_id' => $this->post->id,

    //         'url' => route('posts.show', $this->post),

    //         'button_text' => 'View Post',
    //     ]);
    // }
}
