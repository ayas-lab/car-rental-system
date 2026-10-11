<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nos véhicules') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Filter Bar --}}
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Marque ou modèle..."
                           class="rounded-md border-gray-300 md:col-span-2">

                    <select name="brand" class="rounded-md border-gray-300">
                        <option value="">Toutes les marques</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand }}" @selected(request('brand') === $brand)>
                                {{ $brand }}
                            </option>
                        @endforeach
                    </select>

                    <select name="fuel_type" class="rounded-md border-gray-300">
                        <option value="">Carburant</option>
                        <option value="petrol"   @selected(request('fuel_type') === 'petrol')>Essence</option>
                        <option value="diesel"   @selected(request('fuel_type') === 'diesel')>Diesel</option>
                        <option value="electric" @selected(request('fuel_type') === 'electric')>Électrique</option>
                        <option value="hybrid"   @selected(request('fuel_type') === 'hybrid')>Hybride</option>
                    </select>

                    <select name="transmission" class="rounded-md border-gray-300">
                        <option value="">Transmission</option>
                        <option value="manual"    @selected(request('transmission') === 'manual')>Manuelle</option>
                        <option value="automatic" @selected(request('transmission') === 'automatic')>Automatique</option>
                    </select>

                    <input type="number" name="max_price" value="{{ request('max_price') }}"
                           placeholder="Prix max/jour"
                           class="rounded-md border-gray-300">

                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                           class="rounded-md border-gray-300" title="Date de début">

                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                           class="rounded-md border-gray-300" title="Date de fin">

                    <button class="bg-indigo-600 text-white rounded-md py-2 md:col-span-2 hover:bg-indigo-700">
                        Rechercher
                    </button>

                    <a href="{{ route('vehicles.index') }}"
                       class="border rounded-md py-2 text-center text-sm hover:bg-gray-50">
                        Réinitialiser
                    </a>
                </form>
            </div>

            {{-- Vehicles Grid --}}
            @if ($vehicles->isEmpty())
                <div class="text-center py-16 bg-white rounded-lg shadow">
                    <p class="text-gray-500 text-lg">Aucun véhicule ne correspond à vos critères.</p>
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
                                    <h3 class="font-bold text-lg">
                                        {{ $vehicle->brand }} {{ $vehicle->model }}
                                    </h3>
                                    @if ($vehicle->status === 'available')
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                            Disponible
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                            Loué
                                        </span>
                                    @endif
                                </div>

                                <p class="text-sm text-gray-500 mb-3">
                                    {{ $vehicle->year }} · {{ ucfirst($vehicle->fuel_type) }} ·
                                    {{ $vehicle->seats }} places
                                </p>

                                <p class="text-2xl font-bold text-indigo-600 mb-3">
                                    {{ number_format($vehicle->daily_rate, 2) }} MAD
                                    <span class="text-sm font-normal text-gray-500">/ jour</span>
                                </p>

                                <a href="{{ route('vehicles.show', $vehicle) }}"
                                   class="block text-center px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">
                                    Voir les détails
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $vehicles->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>