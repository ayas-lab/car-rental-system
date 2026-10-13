<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class InvoiceIssued extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Invoice $invoice
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $invoice = $this->invoice;

        return (new MailMessage)
            ->subject('Votre facture ' . $invoice->invoice_number)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre facture est disponible.')
            ->line('**N° :** ' . $invoice->invoice_number)
            ->line('**Montant :** ' . number_format($invoice->total, 2) . ' MAD')
            ->line('**Échéance :** ' . $invoice->due_at->format('d/m/Y'))
            ->action('Voir la facture', route('client.invoices.show', $invoice))
            ->line('Merci de votre confiance.')
            ->salutation('Cordialement, CarRental');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'invoice_id'     => $this->invoice->id,
            'invoice_number' => $this->invoice->invoice_number,
            'total'          => $this->invoice->total,
            'message'        => 'Nouvelle facture : ' . $this->invoice->invoice_number,
        ];
    }
}