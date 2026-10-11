<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        .header { display: flex; justify-content: space-between; margin-bottom: 40px; }
        .company { font-size: 24px; font-weight: bold; color: #4f46e5; }
        .title { font-size: 28px; text-align: right; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        .totals { margin-top: 20px; text-align: right; }
        .totals tr td { padding: 4px 10px; }
        .total-row { font-size: 16px; font-weight: bold; color: #4f46e5; }
        .footer { margin-top: 60px; text-align: center; color: #999; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <div class="company">CarRental</div>
            <div>Casablanca, Maroc</div>
            <div>contact@carental.test</div>
        </div>
        <div class="title">
            <div>FACTURE</div>
            <div style="font-size: 14px;">{{ $invoice->invoice_number }}</div>
            <div style="font-size: 12px; color: #666;">
                Émise le {{ $invoice->issued_at->format('d/m/Y') }}
            </div>
        </div>
    </div>

    <div style="margin-bottom: 30px;">
        <strong>Client :</strong><br>
        {{ $invoice->client->name }}<br>
        {{ $invoice->client->email }}<br>
        {{ $invoice->client->phone }}
    </div>

    <table>
        <thead style="background: #f3f4f6;">
            <tr>
                <th>Description</th>
                <th style="text-align: right;">Montant</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    Location de véhicule<br>
                    <small>
                        {{ $invoice->reservation->vehicle->brand }}
                        {{ $invoice->reservation->vehicle->model }}
                        ({{ $invoice->reservation->vehicle->registration_number }})<br>
                        Du {{ $invoice->reservation->start_date->format('d/m/Y') }}
                        au {{ $invoice->reservation->end_date->format('d/m/Y') }}
                        — {{ $invoice->reservation->total_days }} jour(s)
                    </small>
                </td>
                <td style="text-align: right;">
                    {{ number_format($invoice->subtotal, 2) }} MAD
                </td>
            </tr>
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td>Sous-total</td>
            <td>{{ number_format($invoice->subtotal, 2) }} MAD</td>
        </tr>
        <tr>
            <td>TVA (20%)</td>
            <td>{{ number_format($invoice->tax, 2) }} MAD</td>
        </tr>
        @if ($invoice->discount > 0)
            <tr>
                <td>Remise</td>
                <td>-{{ number_format($invoice->discount, 2) }} MAD</td>
            </tr>
        @endif
        <tr class="total-row">
            <td>Total à payer</td>
            <td>{{ number_format($invoice->total, 2) }} MAD</td>
        </tr>
    </table>

    <div style="margin-top: 40px;">
        <strong>Statut :</strong>
        <span style="color: {{ $invoice->status === 'paid' ? 'green' : 'red' }};">
            {{ strtoupper($invoice->status) }}
        </span>
        <br>
        <strong>Date d'échéance :</strong> {{ $invoice->due_at->format('d/m/Y') }}
    </div>

    <div class="footer">
        Merci pour votre confiance — CarRental © {{ date('Y') }}
    </div>
</body>
</html>