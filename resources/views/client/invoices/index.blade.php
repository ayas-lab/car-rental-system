<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mes factures') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="p-4 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
            @endif

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @php
                    $totalPaid    = $invoices->getCollection()->where('status', 'paid')->sum('total');
                    $totalUnpaid  = $invoices->getCollection()->whereIn('status', ['unpaid', 'overdue'])->sum('total');
                    $totalInvoices = $invoices->total();
                @endphp

                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-500">Total factures</p>
                    <p class="text-3xl font-bold text-indigo-600 mt-1">{{ $totalInvoices }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-500">Montant payé</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">
                        {{ number_format($totalPaid, 2) }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">MAD</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-500">Montant dû</p>
                    <p class="text-3xl font-bold text-red-600 mt-1">
                        {{ number_format($totalUnpaid, 2) }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">MAD</p>
                </div>
            </div>

            {{-- Invoices Table --}}
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">N° facture</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Réservation</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Émise le</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Échéance</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Montant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($invoices as $invoice)
                            <tr>
                                <td class="px-6 py-4 text-sm font-mono">
                                    {{ $invoice->invoice_number }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if ($invoice->reservation)
                                        <a href="{{ route('client.reservations.show', $invoice->reservation) }}"
                                           class="text-indigo-600 hover:underline">
                                            {{ $invoice->reservation->reference }}
                                        </a>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $invoice->issued_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $invoice->due_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold">
                                    {{ number_format($invoice->total, 2) }} MAD
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $colors = [
                                            'unpaid'    => 'bg-yellow-100 text-yellow-800',
                                            'paid'      => 'bg-green-100 text-green-800',
                                            'overdue'   => 'bg-red-100 text-red-800',
                                            'cancelled' => 'bg-gray-100 text-gray-800',
                                        ];
                                        $labels = [
                                            'unpaid'    => 'Impayée',
                                            'paid'      => 'Payée',
                                            'overdue'   => 'En retard',
                                            'cancelled' => 'Annulée',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 text-xs rounded-full {{ $colors[$invoice->status] ?? 'bg-gray-100' }}">
                                        {{ $labels[$invoice->status] ?? ucfirst($invoice->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right space-x-3 whitespace-nowrap">
                                    <a href="{{ route('client.invoices.show', $invoice) }}"
                                       class="text-indigo-600 hover:underline text-sm">Voir</a>
                                    <a href="{{ route('client.invoices.download', $invoice) }}"
                                       class="text-gray-600 hover:underline text-sm">PDF</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <p class="text-lg mb-2">Aucune facture pour le moment.</p>
                                    <p class="text-sm">
                                        Vos factures apparaîtront ici après vos premières locations.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $invoices->links() }}
            </div>
        </div>
    </div>
</x-app-layout>