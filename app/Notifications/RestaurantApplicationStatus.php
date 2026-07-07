<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;


class RestaurantApplicationStatus extends Notification
{
    use Queueable;

    public function __construct(
        public string $status,
        public string $restaurantName
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail($notifiable): MailMessage
    {
        if ($this->status === 'approved') {
            return (new MailMessage)
                ->subject('Restaurant Application Approved')
                ->greeting('Congratulations!')
                ->line("Your restaurant '{$this->restaurantName}' has been approved.")
                ->line('You can now log in and start managing your restaurant.')
                ->action('View Dashboard', url('/restaurant/dashboard'))
                ->line('Thank you for joining Pin+81!');
        }

        return (new MailMessage)
            ->subject('Restaurant Application Rejected')
            ->greeting('Hello,')
            ->line("We're sorry, but your restaurant '{$this->restaurantName}' was not approved.")
            ->line('If you believe this is a mistake or would like to reapply, please contact us.')
            ->action('Contact Support', url('/contact'))
            ->line('Thank you for your interest in Pin+81.');
    }

    public function toArray($notifiable): array
    {
        $data = [
            'title' => $this->status === 'approved'
                ? 'Restaurant Approved'
                : 'Restaurant Rejected',

            'message' => $this->status === 'approved'
                ? "Your restaurant '{$this->restaurantName}' has been approved."
                : "Your restaurant '{$this->restaurantName}' has been rejected.",

            'status' => $this->status,
        ];

        if ($this->status === 'approved') {
            $data['url'] = route('restaurant.dashboard');
            $data['button_text'] = 'Go to Restaurant Dashboard';
        }

        return $data;
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}
