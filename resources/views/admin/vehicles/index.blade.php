<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestion des véhicules') }}
            </h2>
            <a href="{{ route('admin.vehicles.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                + Ajouter un véhicule
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="p-4 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
            @endif

            {{-- Filters --}}
            <form method="GET" class="bg-white rounded-lg shadow p-4 flex gap-3">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Marque, modèle, immatriculation..."
                       class="flex-1 rounded-md border-gray-300">

                <select name="status" class="rounded-md border-gray-300">
                    <option value="">Tous les statuts</option>
                    <option value="available"      @selected(request('status') === 'available')>Disponible</option>
                    <option value="rented"         @selected(request('status') === 'rented')>Loué</option>
                    <option value="maintenance"    @selected(request('status') === 'maintenance')>Maintenance</option>
                    <option value="out_of_service" @selected(request('status') === 'out_of_service')>Hors service</option>
                </select>

                <button class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">
                    Filtrer
                </button>

                <a href="{{ route('admin.vehicles.index') }}"
                   class="px-4 py-2 border rounded-md text-sm">
                    Réinitialiser
                </a>
            </form>

            {{-- Vehicles Grid --}}
            @if ($vehicles->isEmpty())
                <div class="text-center py-16 bg-white rounded-lg shadow">
                    <p class="text-gray-500 text-lg mb-2">Aucun véhicule trouvé.</p>
                    <a href="{{ route('admin.vehicles.create') }}"
                       class="text-indigo-600 hover:underline text-sm">
                        Ajouter votre premier véhicule
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($vehicles as $vehicle)
                        <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition">
                            @if ($vehicle->image_path)
                                <img src="{{ asset('storage/' . $vehicle->image_path) }}"
                                     class="w-full h-48 object-cover" alt="{{ $vehicle->brand }}">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-400">
                                    Pas d'image
                                </div>
                            @endif

                            <div class="p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h3 class="font-bold text-lg">
                                            {{ $vehicle->brand }} {{ $vehicle->model }}
                                        </h3>
                                        <p class="text-sm text-gray-500 font-mono">
                                            {{ $vehicle->registration_number }}
                                        </p>
                                    </div>

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
                                    <span class="px-2 py-1 text-xs rounded-full {{ $statusColors[$vehicle->status] ?? 'bg-gray-100' }}">
                                        {{ $statusLabels[$vehicle->status] ?? ucfirst($vehicle->status) }}
                                    </span>
                                </div>

                                <p class="text-2xl font-bold text-indigo-600 mb-1 mt-3">
                                    {{ number_format($vehicle->daily_rate, 2) }} MAD
                                    <span class="text-sm font-normal text-gray-500">/ jour</span>
                                </p>

                                <p class="text-xs text-gray-500 mb-4">
                                    {{ $vehicle->year }} ·
                                    {{ ucfirst($vehicle->fuel_type) }} ·
                                    {{ number_format($vehicle->mileage) }} km
                                </p>

                                <div class="flex gap-2">
                                    <a href="{{ route('admin.vehicles.show', $vehicle) }}"
                                       class="flex-1 text-center px-3 py-2 bg-gray-100 rounded-md text-sm hover:bg-gray-200">
                                        Voir
                                    </a>
                                    <a href="{{ route('admin.vehicles.edit', $vehicle) }}"
                                       class="flex-1 text-center px-3 py-2 bg-indigo-100 text-indigo-700 rounded-md text-sm hover:bg-indigo-200">
                                        Modifier
                                    </a>
                                    <form method="POST" action="{{ route('admin.vehicles.destroy', $vehicle) }}"
                                          onsubmit="return confirm('Supprimer ce véhicule ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-2 bg-red-100 text-red-700 rounded-md text-sm hover:bg-red-200">
                                            Suppr.
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $vehicles->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>