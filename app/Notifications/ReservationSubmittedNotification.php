<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;


class ReservationSubmittedNotification extends Notification
{
    use Queueable;

    public $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function via($notifiable)
    {
        return ['mail','database', 'broadcast'];
        // Later you can add Broadcast:: ['database', 'broadcast']
    }


    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Reservation Received')
            ->greeting('Hello!')
            ->line('You have received a new reservation.')
            ->line('Customer: ' . $this->reservation->user->name)
            ->line('Date: ' . $this->reservation->reservation_date)
            ->line('Time: ' . $this->reservation->reservation_time)
            ->line('Guests: ' . $this->reservation->num_of_people)
            ->action('View Reservations', url('/restaurant/dashboard'))
            ->line('Thank you for using Pin+81!');
    }

    public function toDatabase($notifiable)
    {
        $customerName = $this->reservation->user?->full_name ?? 'Guest';

        return [
            'type' => 'reservation',
            'reservation_id' => $this->reservation->id,
            'reservation_code' => $this->reservation->reservation_code,
            'customer_name' => $customerName,
            'reservation_date' => $this->reservation->reservation_date,
            'reservation_time' => $this->reservation->reservation_time,
            'num_of_people' => $this->reservation->num_of_people,
            'message' => "{$customerName} submitted a reservation.",
        ];
    }


    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'type' => 'reservation',
            'reservation_id' => $this->reservation->id,
            'reservation_code' => $this->reservation->reservation_code,
            'customer_name' => $this->reservation->user->name,
            'reservation_date' => $this->reservation->reservation_date,
            'reservation_time' => $this->reservation->reservation_time,
            'num_of_people' => $this->reservation->num_of_people,
            'message' => $this->reservation->user->name . ' submitted a reservation.',
        ]);
    }
    
}
