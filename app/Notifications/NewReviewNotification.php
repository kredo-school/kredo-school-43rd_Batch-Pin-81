<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewReviewNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Post $post
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'new_review',

            'title' => 'New Review',

            'message' => $this->post->user->name .
                ' has posted a review for your restaurant.',

            'post_id' => $this->post->id,

            'restaurant_id' => $this->post->restaurant_id,

            'customer_name' => $this->post->user->name,

            'rating' => $this->post->rating,

            'url' => route('restaurant.reviews.index', $this->post),

            'button_text' => 'View Review',
        ];
    }
}
