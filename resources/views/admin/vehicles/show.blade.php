<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $vehicle->brand }} {{ $vehicle->model }}
            </h2>

            <div class="flex gap-2">
                <a href="{{ route('admin.vehicles.edit', $vehicle) }}"
                   class="px-4 py-2 bg-indigo-600 text-black rounded-md text-sm hover:bg-indigo-700">
                    Modifier
                </a>
                <a href="{{ route('admin.vehicles.index') }}"
                   class="px-4 py-2 border rounded-md text-sm">
                    Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif

            {{-- Image + Key Info --}}
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2">
                    <div>
                        @if ($vehicle->image_path)
                            <img src="{{ asset('storage/' . $vehicle->image_path) }}"
                                 class="w-full h-full object-cover min-h-[300px]"
                                 alt="{{ $vehicle->brand }}">
                        @else
                            <div class="w-full h-full min-h-[300px] bg-gray-200 flex items-center justify-center text-gray-400">
                                Pas d'image
                            </div>
                        @endif
                    </div>

                    <div class="p-8">
                        <div class="flex items-center gap-3 mb-4">
                            @php
                                $statusColors = [
                                    'available'      => 'bg-green-100 text-green-800',
                                    'rented'         => 'bg-blue-100 text-blue-800',
                                    'maintenance'    => 'bg-yellow-100 text-yellow-800',
                                    'out_of_service' => 'bg-red-100 text-red-800',
                                ];
                                $statusLabels = [
                                    'available'      => 'Disponible',
                                    'rented'         => 'Loué',
                                    'maintenance'    => 'Maintenance',
                                    'out_of_service' => 'Hors service',
                                ];
                            @endphp
                            <span class="px-3 py-1 text-sm rounded-full {{ $statusColors[$vehicle->status] ?? 'bg-gray-100' }}">
                                {{ $statusLabels[$vehicle->status] ?? ucfirst($vehicle->status) }}
                            </span>
                            <span class="text-sm text-gray-500 font-mono">
                                {{ $vehicle->registration_number }}
                            </span>
                        </div>

                        <p class="text-4xl font-bold text-indigo-600 mb-6">
                            {{ number_format($vehicle->daily_rate, 2) }} MAD
                            <span class="text-base font-normal text-gray-500">/ jour</span>
                        </p>

                        <dl class="grid grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm text-gray-500">Année</dt>
                                <dd class="font-medium">{{ $vehicle->year }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500">Couleur</dt>
                                <dd class="font-medium">{{ $vehicle->color ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500">Carburant</dt>
                                <dd class="font-medium">{{ ucfirst($vehicle->fuel_type) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500">Transmission</dt>
                                <dd class="font-medium">{{ ucfirst($vehicle->transmission) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500">Sièges</dt>
                                <dd class="font-medium">{{ $vehicle->seats }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500">Portes</dt>
                                <dd class="font-medium">{{ $vehicle->doors }}</dd>
                            </div>
                            <div class="col-span-2">
                                <dt class="text-sm text-gray-500">Kilométrage actuel</dt>
                                <dd class="font-medium">{{ number_format($vehicle->mileage) }} km</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            {{-- Description --}}
            @if ($vehicle->description)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-bold text-lg mb-2">Description</h3>
                    <p class="text-gray-700 whitespace-pre-line">{{ $vehicle->description }}</p>
                </div>
            @endif

            {{-- Reservations History --}}
            @if ($vehicle->relationLoaded('reservations') && $vehicle->reservations->isNotEmpty())
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-bold text-lg mb-4">
                        Historique des réservations ({{ $vehicle->reservations->count() }})
                    </h3>
                    <ul class="divide-y divide-gray-100">
                        @foreach ($vehicle->reservations->take(10) as $res)
                            <li class="py-3 flex justify-between items-center">
                                <div>
                                    <p class="font-mono text-sm text-gray-500">{{ $res->reference }}</p>
                                    <p class="font-medium">{{ $res->user->name ?? 'Client inconnu' }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $res->start_date->format('d/m/Y') }}
                                        → {{ $res->end_date->format('d/m/Y') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold">
                                        {{ number_format($res->total_price, 2) }} MAD
                                    </p>
                                    <span class="text-xs px-2 py-1 rounded-full bg-gray-100">
                                        {{ ucfirst($res->status) }}
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Danger Zone --}}
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-400">
                <h3 class="font-bold text-lg mb-2 text-red-700">Zone de danger</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Supprimer ce véhicule le retirera de la flotte (suppression douce).
                    Son historique de réservations sera conservé.
                </p>
                <form method="POST" action="{{ route('admin.vehicles.destroy', $vehicle) }}"
                      onsubmit="return confirm('Supprimer définitivement ce véhicule ?')">
                    @csrf
                    @method('DELETE')
                    <button class="px-4 py-2 bg-red-600 text-white rounded-md text-sm hover:bg-red-700">
                        Supprimer le véhicule
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>