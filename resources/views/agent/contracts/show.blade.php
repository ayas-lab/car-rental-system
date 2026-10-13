<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Contrat ' . $contract->contract_number) }}
            </h2>
            <span class="px-3 py-1 text-sm rounded-full
                {{ $contract->status === 'active' ? 'bg-green-100 text-green-800' :
                   ($contract->status === 'completed' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') }}">
                {{ ucfirst($contract->status) }}
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif

            {{-- Parties --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-500">Client</p>
                    <p class="font-bold">{{ $contract->client->name }}</p>
                    <p class="text-sm text-gray-600">{{ $contract->client->email }}</p>
                    @if ($contract->client->phone)
                        <p class="text-sm text-gray-600">{{ $contract->client->phone }}</p>
                    @endif
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-500">Agent</p>
                    <p class="font-bold">{{ $contract->agent->name }}</p>
                    <p class="text-sm text-gray-600">{{ $contract->agent->email }}</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-500">Véhicule</p>
                    <p class="font-bold">
                        {{ $contract->reservation->vehicle->brand }}
                        {{ $contract->reservation->vehicle->model }}
                    </p>
                    <p class="text-sm text-gray-600 font-mono">
                        {{ $contract->reservation->vehicle->registration_number }}
                    </p>
                </div>
            </div>

            {{-- Reservation Info --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-bold text-lg mb-4">Réservation associée</h3>
                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-gray-500">Référence</dt>
                        <dd class="font-mono">
                            <a href="{{ route('agent.contracts.show', $contract) }}"
                               class="text-indigo-600 hover:underline">
                                {{ $contract->reservation->reference }}
                            </a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Statut</dt>
                        <dd>{{ ucfirst($contract->reservation->status) }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Du</dt>
                        <dd>{{ $contract->reservation->start_date->format('d/m/Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Au</dt>
                        <dd>{{ $contract->reservation->end_date->format('d/m/Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Durée</dt>
                        <dd>{{ $contract->reservation->total_days }} jour(s)</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Total réservation</dt>
                        <dd class="font-semibold">
                            {{ number_format($contract->reservation->total_price, 2) }} MAD
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Check-in --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-bold text-lg mb-4 text-blue-700">🡒 Départ (check-in)</h3>
                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-gray-500">Signé le</dt>
                        <dd class="font-medium">{{ $contract->signed_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Kilométrage</dt>
                        <dd class="font-medium">{{ number_format($contract->pickup_mileage) }} km</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Carburant</dt>
                        <dd class="font-medium">{{ ucfirst($contract->pickup_fuel_level) }}</dd>
                    </div>
                    <div class="col-span-2">
                        <dt class="text-gray-500">État du véhicule</dt>
                        <dd class="font-medium">{{ $contract->pickup_condition ?: 'Aucune observation' }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Check-out (only if returned) --}}
            @if ($contract->returned_at)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-bold text-lg mb-4 text-green-700">🡐 Retour (check-out)</h3>
                    <dl class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="text-gray-500">Retourné le</dt>
                            <dd class="font-medium">{{ $contract->returned_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Kilométrage</dt>
                            <dd class="font-medium">{{ number_format($contract->return_mileage) }} km</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Distance parcourue</dt>
                            <dd class="font-medium">
                                {{ number_format($contract->return_mileage - $contract->pickup_mileage) }} km
                            </dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Carburant</dt>
                            <dd class="font-medium">{{ ucfirst($contract->return_fuel_level) }}</dd>
                        </div>
                        <div class="col-span-2">
                            <dt class="text-gray-500">État du véhicule</dt>
                            <dd class="font-medium">{{ $contract->return_condition ?: 'Aucune observation' }}</dd>
                        </div>
                        @if ($contract->extra_charges > 0)
                            <div class="col-span-2 border-t pt-3">
                                <dt class="text-gray-500">Frais supplémentaires</dt>
                                <dd class="font-semibold text-red-600">
                                    +{{ number_format($contract->extra_charges, 2) }} MAD
                                    @if ($contract->extra_charges_notes)
                                        <span class="text-gray-500 font-normal">
                                            — {{ $contract->extra_charges_notes }}
                                        </span>
                                    @endif
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>
            @else
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                    <p class="text-yellow-800">
                        Ce contrat est encore actif — le véhicule n'a pas été retourné.
                    </p>
                </div>
            @endif

            {{-- Invoice Link --}}
            @if ($contract->reservation->invoice)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-bold text-lg mb-3">Facture générée</h3>
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-mono">{{ $contract->reservation->invoice->invoice_number }}</p>
                            <p class="text-sm text-gray-500">
                                {{ number_format($contract->reservation->invoice->total, 2) }} MAD —
                                {{ ucfirst($contract->reservation->invoice->status) }}
                            </p>
                        </div>
                        <a href="{{ route('admin.invoices.show', $contract->reservation->invoice) }}"
                           class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm">
                            Voir la facture
                        </a>
                    </div>
                </div>
            @endif

            {{-- Actions --}}
            <div class="flex justify-between items-center">
                <a href="{{ route('agent.contracts.index') }}"
                   class="text-indigo-600 hover:underline text-sm">
                    ← Retour aux contrats
                </a>

                @if ($contract->status === 'active')
                    <a href="{{ route('agent.checkout.create', $contract) }}"
                       class="px-6 py-2 bg-indigo-600 text-black rounded-md text-sm hover:bg-indigo-700">
                        Traiter le retour
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>