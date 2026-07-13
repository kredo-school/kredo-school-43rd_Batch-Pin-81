<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


class CustomerRunningLateNotification extends Notification
{
    use Queueable;

    public Reservation $reservation;
    public ?int $lateMinutes;

    public function __construct(Reservation $reservation, ?int $lateMinutes = null)
    {
        $this->reservation = $reservation;
        $this->lateMinutes = $lateMinutes;
    }

    /**
     * Delivery channels.
     */
    public function via(object $notifiable): array
    {
        return [
            'database',
            // 'mail', 
            // 'broadcast'
        ];
    }

    /**
     * Database notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'customer_running_late',
            'title' => 'Customer Running Late',
            'message' => $this->reservation->user->name .
                ' has informed you they are running late.',
            'reservation_id' => $this->reservation->id,
            'customer_name' => $this->reservation->user->name,
            'reservation_date' => $this->reservation->reservation_date,
            'reservation_time' => $this->reservation->reservation_time,
            'num_of_people' => $this->reservation->num_of_people,
            'reservation_code' => $this->reservation->reservation_code,
            'late_minutes' => $this->lateMinutes,
            'url' => route('restaurant.reservations.show', $this->reservation),
            'button_text' => 'View Reservation',
        ];
    }

    /**
     * Email notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     $mail = (new MailMessage)
    //         ->subject('Customer Running Late')
    //         ->greeting('Hello!')
    //         ->line($this->reservation->user->name . ' has informed you they will be late.');

    //     if ($this->lateMinutes) {
    //         $mail->line('Estimated delay: ' . $this->lateMinutes . ' minutes.');
    //     }

    //     return $mail
    //         ->line('Reservation Date: ' . $this->reservation->reservation_date)
    //         ->line('Reservation Time: ' . $this->reservation->reservation_time)
    //         ->line('Guests: ' . $this->reservation->num_of_people)
    //         ->action(
    //             'View Reservation',
    //             route('restaurant.reservations.show', $this->reservation)
    //         );
    // }

    /**
     * Broadcast notification.
     */
    // public function toBroadcast(object $notifiable): BroadcastMessage
    // {
    //     return new BroadcastMessage([
    //         'type' => 'customer_running_late',
    //         'title' => 'Customer Running Late',
    //         'message' => $this->reservation->user->name .
    //             ' has informed you they are running late.',
    //         'reservation_id' => $this->reservation->id,
    //         'customer_name' => $this->reservation->user->name,
    //         'reservation_date' => $this->reservation->reservation_date,
    //         'reservation_time' => $this->reservation->reservation_time,
    //         'num_of_people' => $this->reservation->num_of_people,
    //         'reservation_code' => $this->reservation->reservation_code,
    //         'late_minutes' => $this->lateMinutes,
    //         'url' => route('restaurant.reservations.show', $this->reservation),
    //         'button_text' => 'View Reservation',
    //     ]);
    // }
}
