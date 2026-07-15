<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;


class ReservationConfirmedNotification extends Notification
{
    use Queueable;

    public $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function via($notifiable)
    {
        return [
            'mail',
            'database',
            // 'broadcast'
        ];
    }


    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Reservation Accepted')
            ->greeting('Hello!')
            ->line('Your reservation has been accepted by the restaurant.')
            ->line('Reservation Code: ' . $this->reservation->reservation_code)
            ->line('Date: ' . $this->reservation->reservation_date)
            ->line('Time: ' . $this->reservation->reservation_time)
            ->line('Guests: ' . $this->reservation->num_of_people)
            ->action('View Reservation', route('my_reservations'))
            ->line('Thank you for using Pin+81!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'reservation',
            'title' => 'Reservation Accepted',
            'reservation_id' => $this->reservation->id,
            'reservation_code' => $this->reservation->reservation_code,
            'customer_name' => $this->reservation->user?->full_name ?? 'Guest',
            'reservation_date' => $this->reservation->reservation_date,
            'reservation_time' => $this->reservation->reservation_time,
            'num_of_people' => $this->reservation->num_of_people,
            'status' => 'confirmed',
            'message' => 'Your reservation has been accepted by the restaurant.',
            'url' => route('my_reservations'),
            'button_text' => 'View Reservation',
        ];
    }


    // public function toBroadcast($notifiable)
    // {
    //     return new BroadcastMessage([
    //         'type' => 'reservation',
    //         'reservation_id' => $this->reservation->id,
    //         'reservation_code' => $this->reservation->reservation_code,
    //         'customer_name' => $this->reservation->user->name,
    //         'reservation_date' => $this->reservation->reservation_date,
    //         'reservation_time' => $this->reservation->reservation_time,
    //         'num_of_people' => $this->reservation->num_of_people,
    //         'message' => $this->reservation->user->name . ' submitted a reservation.',
    //     ]);
    // }
}
