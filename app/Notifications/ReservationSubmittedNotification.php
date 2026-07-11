<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

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
        return ['database'];
        // Later you can add Broadcast:: ['database', 'broadcast']
    }

    public function toDatabase($notifiable)
    {
        $customerName = $this->reservation->user?->full_name ?? 'Guest';

        return [
            'reservation_id' => $this->reservation->id,
            'reservation_code' => $this->reservation->reservation_code,
            'customer_name' => $customerName,
            'reservation_date' => $this->reservation->reservation_date,
            'reservation_time' => $this->reservation->reservation_time,
            'num_of_people' => $this->reservation->num_of_people,
            'message' => "{$customerName} submitted a reservation.",
        ];
    }
}
