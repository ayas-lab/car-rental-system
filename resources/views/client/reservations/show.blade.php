<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Réservation') }} {{ $reservation->reference }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white rounded-lg shadow p-6">
                <dl class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <dt class="text-sm text-gray-500">Véhicule</dt>
                        <dd class="font-medium">
                            {{ $reservation->vehicle->brand }} {{ $reservation->vehicle->model }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Statut</dt>
                        <dd class="font-medium">{{ ucfirst($reservation->status) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Date de début</dt>
                        <dd class="font-medium">{{ $reservation->start_date->format('d/m/Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Date de fin</dt>
                        <dd class="font-medium">{{ $reservation->end_date->format('d/m/Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Durée</dt>
                        <dd class="font-medium">{{ $reservation->total_days }} jour(s)</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Tarif journalier</dt>
                        <dd class="font-medium">{{ number_format($reservation->daily_rate, 2) }} MAD</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Sous-total</dt>
                        <dd class="font-medium">{{ number_format($reservation->subtotal, 2) }} MAD</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">TVA</dt>
                        <dd class="font-medium">{{ number_format($reservation->tax, 2) }} MAD</dd>
                    </div>
                </dl>

                <div class="border-t pt-4">
                    <div class="flex justify-between items-center text-xl font-bold text-indigo-700">
                        <span>Total</span>
                        <span>{{ number_format($reservation->total_price, 2) }} MAD</span>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <a href="{{ route('client.reservations.index') }}"
                       class="px-4 py-2 border rounded-md text-sm">Retour</a>

                    @if (in_array($reservation->status, ['pending', 'confirmed']))
                        <form method="POST" action="{{ route('client.reservations.cancel', $reservation) }}"
                              onsubmit="return confirm('Annuler cette réservation ?')">
                            @csrf
                            @method('DELETE')
                            <button class="px-4 py-2 bg-red-100 text-red-700 rounded-md text-sm hover:bg-red-200">
                                Annuler la réservation
                            </button>
                        </form>
                    @endif

                    @if ($reservation->invoice)
                        <a href="{{ route('client.invoices.show', $reservation->invoice) }}"
                           class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm">
                            Voir la facture
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>