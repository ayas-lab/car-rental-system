<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Réservation ' . $reservation->reference) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="p-4 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
            @endif

            {{-- Status Actions --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-bold text-lg mb-4">Actions</h3>

                <div class="flex gap-3 flex-wrap">
                    @if ($reservation->status === 'pending')
                        <form method="POST" action="{{ route('admin.reservations.status', $reservation) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="confirmed">
                            <button class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm">
                                Confirmer
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.reservations.status', $reservation) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="cancelled">
                            <button class="px-4 py-2 bg-red-100 text-red-700 rounded-md text-sm">
                                Annuler
                            </button>
                        </form>
                    @elseif ($reservation->status === 'confirmed')
                        <form method="POST" action="{{ route('admin.reservations.status', $reservation) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="active">
                            <button class="px-4 py-2 bg-green-600 text-white rounded-md text-sm">
                                Marquer comme actif
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.reservations.status', $reservation) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="cancelled">
                            <button class="px-4 py-2 bg-red-100 text-red-700 rounded-md text-sm">
                                Annuler
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('admin.reservations.edit', $reservation) }}"
                       class="px-4 py-2 bg-gray-100 rounded-md text-sm">Modifier</a>
                </div>
            </div>

            {{-- Details --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-bold text-lg mb-4">Détails</h3>
                <dl class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm text-gray-500">Client</dt>
                        <dd class="font-medium">
                            <a href="{{ route('admin.users.show', $reservation->user) }}"
                               class="text-indigo-600 hover:underline">
                                {{ $reservation->user->name }}
                            </a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Véhicule</dt>
                        <dd class="font-medium">
                            {{ $reservation->vehicle->brand }} {{ $reservation->vehicle->model }}
                            <span class="text-gray-500 font-mono text-sm">
                                ({{ $reservation->vehicle->registration_number }})
                            </span>
                        </dd>
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
                    <div class="col-span-2 pt-4 border-t">
                        <dt class="text-sm text-gray-500">Total</dt>
                        <dd class="font-bold text-2xl text-indigo-600">
                            {{ number_format($reservation->total_price, 2) }} MAD
                        </dd>
                    </div>
                </dl>
            </div>

            @if ($reservation->contract)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-bold text-lg mb-4">Contrat</h3>
                    <p class="text-sm">
                        <strong>{{ $reservation->contract->contract_number }}</strong>
                        — Statut : {{ $reservation->contract->status }}
                    </p>
                </div>
            @endif

            @if ($reservation->invoice)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-bold text-lg mb-4">Facture</h3>
                    <p class="text-sm">
                        <strong>{{ $reservation->invoice->invoice_number }}</strong>
                        — {{ number_format($reservation->invoice->total, 2) }} MAD —
                        {{ $reservation->invoice->status }}
                    </p>
                </div>
            @endif

            <div>
                <a href="{{ route('admin.reservations.index') }}"
                   class="text-indigo-600 hover:underline text-sm">← Retour aux réservations</a>
            </div>
        </div>
    </div>
</x-app-layout>