<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Facture ' . $invoice->invoice_number) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-bold text-lg mb-4">Changer le statut</h3>
                <form method="POST" action="{{ route('admin.invoices.update', $invoice) }}"
                      class="flex gap-3">
                    @csrf
                    @method('PUT')
                    <select name="status" class="flex-1 rounded-md border-gray-300">
                        @foreach (['unpaid', 'paid', 'overdue', 'cancelled'] as $status)
                            <option value="{{ $status }}" @selected($invoice->status === $status)>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm">
                        Mettre à jour
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-bold text-lg mb-4">Détails</h3>
                <dl class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm text-gray-500">Client</dt>
                        <dd class="font-medium">{{ $invoice->client->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Réservation</dt>
                        <dd class="font-mono">{{ $invoice->reservation->reference }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Sous-total</dt>
                        <dd class="font-medium">{{ number_format($invoice->subtotal, 2) }} MAD</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">TVA</dt>
                        <dd class="font-medium">{{ number_format($invoice->tax, 2) }} MAD</dd>
                    </div>
                    <div class="col-span-2 pt-4 border-t">
                        <dt class="text-sm text-gray-500">Total</dt>
                        <dd class="font-bold text-2xl text-indigo-600">
                            {{ number_format($invoice->total, 2) }} MAD
                        </dd>
                    </div>
                </dl>

                <div class="mt-6 flex gap-3">
                    <a href="{{ route('admin.invoices.download', $invoice) }}"
                       class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm">
                        Télécharger PDF
                    </a>
                    <a href="{{ route('admin.invoices.index') }}"
                       class="px-4 py-2 border rounded-md text-sm">Retour</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>