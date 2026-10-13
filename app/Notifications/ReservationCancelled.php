<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;


class ReservationCancelled extends Notification implements ShouldQueue
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
        return (new MailMessage)
            ->subject('Réservation annulée — ' . $this->reservation->reference)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre réservation a été annulée.')
            ->line('**Référence :** ' . $this->reservation->reference)
            ->line('**Véhicule :** ' . $this->reservation->vehicle->brand . ' ' . $this->reservation->vehicle->model)
            ->action('Voir l\'historique', route('client.reservations.index'))
            ->line('Nous espérons vous revoir bientôt sur CarRental.')
            ->salutation('Cordialement');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'reservation_id' => $this->reservation->id,
            'reference'      => $this->reservation->reference,
            'message'        => 'Réservation ' . $this->reservation->reference . ' annulée.',
        ];
    }
}