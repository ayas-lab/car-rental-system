<?php

namespace App\Notifications;

use App\Models\Contract;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class VehicleReturned extends Notification implements ShouldQueue
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

        return (new MailMessage)
            ->subject('Retour enregistré — Merci !')
            ->greeting('Merci ' . $notifiable->name . ' !')
            ->line('Nous avons bien enregistré le retour de votre véhicule.')
            ->line('**Contrat :** ' . $contract->contract_number)
            ->line('**Kilométrage au retour :** ' . number_format($contract->return_mileage) . ' km')
            ->line('**Total parcouru :** ' . number_format($contract->return_mileage - $contract->pickup_mileage) . ' km')
            ->line('**Date de retour :** ' . $contract->returned_at?->format('d/m/Y H:i'))
            ->line('Votre facture sera bientôt disponible.')
            ->salutation('À bientôt sur CarRental !');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'contract_id'    => $this->contract->id,
            'contract_number'=> $this->contract->contract_number,
            'message'        => 'Véhicule retourné — contrat ' . $this->contract->contract_number,
        ];
    }
}