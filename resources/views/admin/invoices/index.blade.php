<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Factures') }}
            </h2>
            <div class="text-sm text-gray-600">
                Total payé : <strong class="text-green-600">
                    {{ number_format(\App\Models\Invoice::where('status', 'paid')->sum('total'), 2) }} MAD
                </strong>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
            @endif

            <form method="GET" class="bg-white rounded-lg shadow p-4 mb-6 flex gap-3">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Numéro ou client..."
                       class="flex-1 rounded-md border-gray-300">
                <select name="status" class="rounded-md border-gray-300">
                    <option value="">Tous les statuts</option>
                    <option value="unpaid"    @selected(request('status') === 'unpaid')>Impayée</option>
                    <option value="paid"      @selected(request('status') === 'paid')>Payée</option>
                    <option value="overdue"   @selected(request('status') === 'overdue')>En retard</option>
                    <option value="cancelled" @selected(request('status') === 'cancelled')>Annulée</option>
                </select>
                <button class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">Filtrer</button>
            </form>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">N°</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Réservation</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Échéance</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($invoices as $inv)
                            <tr>
                                <td class="px-4 py-3 text-sm font-mono">{{ $inv->invoice_number }}</td>
                                <td class="px-4 py-3 text-sm">{{ $inv->client->name }}</td>
                                <td class="px-4 py-3 text-sm font-mono">{{ $inv->reservation->reference }}</td>
                                <td class="px-4 py-3 text-sm font-semibold">
                                    {{ number_format($inv->total, 2) }} MAD
                                </td>
                                <td class="px-4 py-3 text-sm">{{ $inv->due_at->format('d/m/Y') }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $colors = [
                                            'unpaid'    => 'bg-yellow-100 text-yellow-800',
                                            'paid'      => 'bg-green-100 text-green-800',
                                            'overdue'   => 'bg-red-100 text-red-800',
                                            'cancelled' => 'bg-gray-100 text-gray-800',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 text-xs rounded-full {{ $colors[$inv->status] }}">
                                        {{ ucfirst($inv->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right space-x-2">
                                    <a href="{{ route('admin.invoices.show', $inv) }}"
                                       class="text-indigo-600 hover:underline text-sm">Voir</a>
                                    <a href="{{ route('admin.invoices.download', $inv) }}"
                                       class="text-gray-600 hover:underline text-sm">PDF</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    Aucune facture.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">{{ $invoices->links() }}</div>
        </div>
    </div>
</x-app-layout>