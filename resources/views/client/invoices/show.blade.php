<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Facture ' . $invoice->invoice_number) }}
            </h2>
            <a href="{{ route('client.invoices.download', $invoice) }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Télécharger PDF
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Invoice Header --}}
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-8 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-3xl font-bold">FACTURE</h1>
                            <p class="text-indigo-100 font-mono mt-1">{{ $invoice->invoice_number }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-lg">CarRental</p>
                            <p class="text-sm text-indigo-100">Casablanca, Maroc</p>
                            <p class="text-sm text-indigo-100">contact@carental.test</p>
                        </div>
                    </div>
                </div>

                {{-- Status Banner --}}
                @php
                    $statusStyles = [
                        'unpaid'    => 'bg-yellow-50 border-yellow-400 text-yellow-800',
                        'paid'      => 'bg-green-50 border-green-400 text-green-800',
                        'overdue'   => 'bg-red-50 border-red-400 text-red-800',
                        'cancelled' => 'bg-gray-50 border-gray-400 text-gray-700',
                    ];
                    $statusLabels = [
                        'unpaid'    => 'Impayée',
                        'paid'      => 'Payée',
                        'overdue'   => 'En retard',
                        'cancelled' => 'Annulée',
                    ];
                @endphp
                <div class="border-l-4 {{ $statusStyles[$invoice->status] ?? 'bg-gray-50' }} p-4">
                    <p class="font-medium">
                        Statut : {{ $statusLabels[$invoice->status] ?? ucfirst($invoice->status) }}
                        @if ($invoice->paid_at)
                            — payée le {{ $invoice->paid_at->format('d/m/Y') }}
                        @endif
                    </p>
                </div>
            </div>

            {{-- Billing Info --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-sm font-medium text-gray-500 uppercase mb-3">Facturé à</h3>
                    <p class="font-bold text-lg">{{ $invoice->client->name }}</p>
                    <p class="text-sm text-gray-600">{{ $invoice->client->email }}</p>
                    @if ($invoice->client->phone)
                        <p class="text-sm text-gray-600">{{ $invoice->client->phone }}</p>
                    @endif
                    @if ($invoice->client->address)
                        <p class="text-sm text-gray-600 mt-2">
                            {{ $invoice->client->address }}<br>
                            {{ $invoice->client->city }}{{ $invoice->client->country ? ', ' . $invoice->client->country : '' }}
                        </p>
                    @endif
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-sm font-medium text-gray-500 uppercase mb-3">Détails</h3>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Date d'émission</dt>
                            <dd class="font-medium">{{ $invoice->issued_at->format('d/m/Y') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Date d'échéance</dt>
                            <dd class="font-medium">{{ $invoice->due_at->format('d/m/Y') }}</dd>
                        </div>
                        @if ($invoice->paid_at)
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Date de paiement</dt>
                                <dd class="font-medium text-green-600">
                                    {{ $invoice->paid_at->format('d/m/Y') }}
                                </dd>
                            </div>
                        @endif
                        @if ($invoice->reservation)
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Réservation</dt>
                                <dd class="font-medium font-mono">
                                    <a href="{{ route('client.reservations.show', $invoice->reservation) }}"
                                       class="text-indigo-600 hover:underline">
                                        {{ $invoice->reservation->reference }}
                                    </a>
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            {{-- Line Items --}}
            @if ($invoice->reservation)
                @php $reservation = $invoice->reservation; @endphp
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Description
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                    Montant
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4">
                                    <p class="font-medium">
                                        Location — {{ $reservation->vehicle->brand }}
                                        {{ $reservation->vehicle->model }}
                                    </p>
                                    <p class="text-sm text-gray-500 font-mono mt-1">
                                        {{ $reservation->vehicle->registration_number }}
                                    </p>
                                    <p class="text-sm text-gray-500 mt-1">
                                        Du {{ $reservation->start_date->format('d/m/Y') }}
                                        au {{ $reservation->end_date->format('d/m/Y') }}
                                        — {{ $reservation->total_days }} jour(s)
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        Tarif journalier : {{ number_format($reservation->daily_rate, 2) }} MAD
                                    </p>
                                </td>
                                <td class="px-6 py-4 text-right font-medium whitespace-nowrap">
                                    {{ number_format($invoice->subtotal, 2) }} MAD
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif

            {{-- Totals --}}
            <div class="bg-white rounded-lg shadow p-6">
                <div class="max-w-sm ml-auto space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Sous-total</span>
                        <span class="font-medium">{{ number_format($invoice->subtotal, 2) }} MAD</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">TVA (20%)</span>
                        <span class="font-medium">{{ number_format($invoice->tax, 2) }} MAD</span>
                    </div>
                    @if ($invoice->discount > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Remise</span>
                            <span class="font-medium text-green-600">
                                -{{ number_format($invoice->discount, 2) }} MAD
                            </span>
                        </div>
                    @endif
                    <div class="flex justify-between text-lg font-bold text-indigo-700 pt-3 border-t">
                        <span>Total</span>
                        <span>{{ number_format($invoice->total, 2) }} MAD</span>
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            @if ($invoice->notes)
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                    <p class="text-sm text-blue-800">
                        <strong>Note :</strong> {{ $invoice->notes }}
                    </p>
                </div>
            @endif

            {{-- Actions --}}
            <div class="flex justify-between items-center">
                <a href="{{ route('client.invoices.index') }}"
                   class="text-indigo-600 hover:underline text-sm">
                    ← Retour à mes factures
                </a>
                <a href="{{ route('client.invoices.download', $invoice) }}"
                   class="px-6 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">
                    Télécharger la facture PDF
                </a>
            </div>
        </div>
    </div>
</x-app-layout>