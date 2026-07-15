<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


class ChangedReservationNotification extends Notification
{
    use Queueable;

    public Reservation $reservation;
    public array $changes;

    public function __construct(Reservation $reservation, array $changes = [])
    {
        $this->reservation = $reservation;
        $this->changes = $changes;
    }

    /**
     * Delivery channels.
     */
    public function via(object $notifiable): array
    {
        return [
            'database',
            // 'broadcast'
        ];
    }

    /**
     * Store in database.
     */
    public function toDatabase(object $notifiable): array
    {
        $changedFields = collect($this->changes)
            ->map(function (array $change, string $field): array {
                return [
                    'field' => $field,
                    'label' => $change['label'] ?? ucfirst(str_replace('_', ' ', $field)),
                    'before' => $change['before'] ?? null,
                    'after' => $change['after'] ?? null,
                ];
            })
            ->values()
            ->all();

        $summary = !empty($changedFields)
            ? 'Updated: ' . collect($changedFields)->pluck('label')->implode(', ')
            : "{$this->reservation->user->name} updated their reservation.";

        return [
            'type' => 'changed_reservation',
            'title' => 'Reservation Updated',
            'message' => $summary,
            'reservation_id' => $this->reservation->id,
            'customer_name' => $this->reservation->user->name,
            'reservation_date' => $this->reservation->reservation_date,
            'reservation_time' => $this->reservation->reservation_time,
            'num_of_people' => $this->reservation->num_of_people,
            'reservation_code' => $this->reservation->reservation_code,
            'changed_fields' => $changedFields,
            'url' => route('restaurant.reservations'),
            'button_text' => 'View Reservation',
        ];
    }

    /**
     * Mail notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //         ->subject('Reservation Updated')
    //         ->greeting('Hello!')
    //         ->line("{$this->reservation->user->name} has updated their reservation.")
    //         ->line('Updated Reservation Details:')
    //         ->line('Date: ' . $this->reservation->reservation_date)
    //         ->line('Time: ' . $this->reservation->reservation_time)
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
    //         'type' => 'changed_reservation',
    //         'title' => 'Reservation Updated',
    //         'message' => "{$this->reservation->user->name} updated their reservation.",
    //         'reservation_id' => $this->reservation->id,
    //         'customer_name' => $this->reservation->user->name,
    //         'reservation_date' => $this->reservation->reservation_date,
    //         'reservation_time' => $this->reservation->reservation_time,
    //         'num_of_people' => $this->reservation->num_of_people,
    //         'reservation_code' => $this->reservation->reservation_code,
    //         'url' => route('restaurant.reservations.show', $this->reservation),
    //         'button_text' => 'View Reservation',
    //     ]);
    // }
}
