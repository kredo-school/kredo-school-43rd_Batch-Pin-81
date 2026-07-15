<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationCanceledByRestaurantNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Reservation $reservation
    ) {}

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
        // Add 'broadcast' if you're using realtime notifications
    }

    /**
     * Store notification in database.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'reservation_canceled',

            'status' => 'cancelled',

            'title' => 'Reservation has been cancelled',

            'message' => 'Your reservation at '
                . $this->reservation->restaurant->restaurant_name
                . ' has been cancelled by the restaurant.',

            'restaurant_name' => $this->reservation->restaurant->restaurant_name,

            'reservation_id' => $this->reservation->id,

            'reservation_date' => $this->reservation->reservation_date,

            'reservation_time' => $this->reservation->reservation_time,

            'url' => route('my_reservations'),

            'button_text' => 'View Reservation',
        ];
    }

    /**
     * Email notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Reservation Has Been Cancelled')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Unfortunately, the restaurant has cancelled your reservation.')
            ->line('Restaurant: ' . $this->reservation->restaurant->restaurant_name)
            ->line('Date: ' . $this->reservation->reservation_date)
            ->line('Time: ' . $this->reservation->reservation_time)
            ->action(
                'View Reservation',
                route('my_reservations')
            )
            ->line('We apologize for the inconvenience.');
    }

    /**
     * Broadcast payload (optional).
     */
    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
