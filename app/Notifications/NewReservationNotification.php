<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\BroadcastMessage;

class NewReservationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Reservation $reservation) {}

    /**
     * Delivery channels.
     */
    public function via(object $notifiable): array
    {
        return [
            'database',
            'mail',
            //  'broadcast'
        ];
    }

    /**
     * Database notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'new_reservation',
            'title' => 'New Reservation',
            'message' => 'You have received a new reservation.',
            'reservation_id' => $this->reservation->id,
            'customer_name' => optional($this->reservation->user)->name ?? 'Guest',
            'reservation_date' => $this->reservation->reservation_date,
            'reservation_time' => $this->reservation->reservation_time,
            'num_of_people' => $this->reservation->num_of_people,
            'reservation_code' => $this->reservation->reservation_code,
            'url' => route('restaurant.reservations', $this->reservation),
        ];
    }

    /**
     * Email notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Reservation')
            ->greeting('Hello!')
            ->line('You have received a new reservation.')
            ->line('Customer: ' . (optional($this->reservation->user)->name ?? 'Guest'))
            ->line('Date: ' . $this->reservation->reservation_date)
            ->line('Time: ' . $this->reservation->reservation_time)
            ->line('People: ' . $this->reservation->num_of_people)
            ->action(
                'View Reservation',
                route('restaurant.reservations', $this->reservation)
            )
            ->line('Thank you for using our platform!');
    }

    /**
     * Broadcast notification.
     */
    // public function toBroadcast(object $notifiable): BroadcastMessage
    // {
    //     return new BroadcastMessage([
    //         'type' => 'new_reservation',
    //         'title' => 'New Reservation',
    //         'message' => 'You have received a new reservation.',
    //         'reservation_id' => $this->reservation->id,
    //         'customer_name' => optional($this->reservation->user)->name ?? 'Guest',
    //         'reservation_date' => $this->reservation->reservation_date,
    //         'reservation_time' => $this->reservation->reservation_time,
    //         'num_of_people' => $this->reservation->num_of_people,
    //         'url' => route('restaurant.reservations.show', $this->reservation),
    //     ]);
    // }

    /**
     * Array representation.
     */
    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
