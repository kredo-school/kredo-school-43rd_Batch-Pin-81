<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class ReviewReportedNotification extends Notification
{
    public function __construct(public Post $post) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $restaurant = $this->post->restaurant;
        $reporter = $this->post->user;

        return [
            'type' => 'review_report',
            'title' => 'Review Reported',
            'message' => $restaurant
                ? "A review for {$restaurant->restaurant_name} was reported by {$reporter?->username}."
                : 'A review was reported by a customer.',
            'post_id' => $this->post->id,
            'restaurant_id' => $this->post->restaurant_id,
            'restaurant_name' => $restaurant?->restaurant_name,
            'reported_by' => $reporter?->username ?? $reporter?->full_name ?? 'Unknown User',
            'rating' => $this->post->rating,
            'description' => Str::limit($this->post->description ?? '', 180),
            'url' => route('admin.reviews', ['tab' => 'reported']),
            'button_text' => 'Open Reported Reviews',
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
