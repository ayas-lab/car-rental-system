<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;


class ReservationConfirmed extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Reservation $reservation
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $reservation = $this->reservation;

        return (new MailMessage)
            ->subject('Réservation confirmée — ' . $reservation->reference)
            ->greeting('Bonne nouvelle, ' . $notifiable->name . ' !')
            ->line('Votre réservation a été confirmée par notre équipe.')
            ->line('**Référence :** ' . $reservation->reference)
            ->line('**Véhicule :** ' . $reservation->vehicle->brand . ' ' . $reservation->vehicle->model)
            ->line('**Du :** ' . $reservation->start_date->format('d/m/Y'))
            ->line('**Au :** ' . $reservation->end_date->format('d/m/Y'))
            ->line('**Lieu de prise en charge :** ' . ($reservation->pickup_location ?? 'Agence principale'))
            ->action('Voir les détails', route('client.reservations.show', $reservation))
            ->line('Présentez-vous avec votre permis de conduire et une pièce d\'identité.')
            ->salutation('À bientôt !');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'reservation_id' => $this->reservation->id,
            'reference'      => $this->reservation->reference,
            'message'        => 'Votre réservation ' . $this->reservation->reference . ' est confirmée.',
        ];
    }
}