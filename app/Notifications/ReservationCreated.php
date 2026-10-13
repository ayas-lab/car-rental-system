<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;


class ReservationCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Reservation $reservation
    ) {}

    /**
     * Delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Email representation.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $reservation = $this->reservation;

        return (new MailMessage)
            ->subject('Réservation confirmée — ' . $reservation->reference)
            ->greeting('Bonjour ' . $notifiable->name . ' !')
            ->line('Votre réservation a bien été enregistrée.')
            ->line('**Référence :** ' . $reservation->reference)
            ->line('**Véhicule :** ' . $reservation->vehicle->brand . ' ' . $reservation->vehicle->model)
            ->line('**Du :** ' . $reservation->start_date->format('d/m/Y'))
            ->line('**Au :** ' . $reservation->end_date->format('d/m/Y'))
            ->line('**Total :** ' . number_format($reservation->total_price, 2) . ' MAD')
            ->action('Voir ma réservation', route('client.reservations.show', $reservation))
            ->line('Nous vous contacterons dès que votre réservation sera confirmée.')
            ->salutation('L\'équipe CarRental');
    }

    /**
     * Database representation (for the in-app bell).
     */
    public function toArray(object $notifiable): array
    {
        return [
            'reservation_id' => $this->reservation->id,
            'reference'      => $this->reservation->reference,
            'vehicle'        => $this->reservation->vehicle->brand . ' ' . $this->reservation->vehicle->model,
            'start_date'     => $this->reservation->start_date->format('Y-m-d'),
            'total'          => $this->reservation->total_price,
            'message'        => 'Réservation ' . $this->reservation->reference . ' créée.',
        ];
    }
}