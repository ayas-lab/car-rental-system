<?php

namespace App\Notifications;

use App\Models\Contract;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class VehiclePickedUp extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Contract $contract
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $contract = $this->contract;
        $vehicle  = $contract->reservation->vehicle;

        return (new MailMessage)
            ->subject('Bon voyage !')
            ->greeting('Bonne route, ' . $notifiable->name . ' !')
            ->line('Votre véhicule a été remis avec succès.')
            ->line('**Contrat :** ' . $contract->contract_number)
            ->line('**Véhicule :** ' . $vehicle->brand . ' ' . $vehicle->model)
            ->line('**Kilométrage au départ :** ' . number_format($contract->pickup_mileage) . ' km')
            ->line('**Carburant :** ' . $contract->pickup_fuel_level)
            ->action('Voir mon contrat', route('client.reservations.show', $contract->reservation_id))
            ->line('En cas de problème, contactez notre agence.')
            ->salutation('Bon voyage !');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'contract_id'    => $this->contract->id,
            'contract_number'=> $this->contract->contract_number,
            'message'        => 'Véhicule récupéré — contrat ' . $this->contract->contract_number,
        ];
    }
}