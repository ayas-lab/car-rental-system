<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $vehicle->brand }} {{ $vehicle->model }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
            @endif

            <div class="bg-white rounded-lg shadow overflow-hidden grid grid-cols-1 md:grid-cols-2">

                {{-- Image --}}
                <div>
                    @if ($vehicle->image_path)
                        <img src="{{ asset('storage/' . $vehicle->image_path) }}"
                             class="w-full h-full object-cover" alt="{{ $vehicle->brand }}">
                    @else
                        <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400 min-h-[400px]">
                            Pas d'image
                        </div>
                    @endif
                </div>

                {{-- Details --}}
                <div class="p-8">
                    <h1 class="text-3xl font-bold mb-2">
                        {{ $vehicle->brand }} {{ $vehicle->model }}
                    </h1>
                    <p class="text-gray-500 mb-4">{{ $vehicle->registration_number }}</p>

                    <p class="text-3xl font-bold text-indigo-600 mb-6">
                        {{ number_format($vehicle->daily_rate, 2) }} MAD
                        <span class="text-lg font-normal text-gray-500">/ jour</span>
                    </p>

                    <dl class="grid grid-cols-2 gap-4 mb-6">
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
                    </dl>

                    @if ($vehicle->description)
                        <p class="text-gray-700 mb-6">{{ $vehicle->description }}</p>
                    @endif

                    @auth
                        @if (auth()->user()->hasRole('client'))
                            <a href="{{ route('client.reservations.create', $vehicle) }}"
                               class="block text-center px-6 py-3 bg-indigo-600 text-white rounded-md font-semibold hover:bg-indigo-700">
                                Réserver ce véhicule
                            </a>
                        @else
                            <p class="text-sm text-gray-500 text-center">
                                Seuls les clients peuvent réserver.
                            </p>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                           class="block text-center px-6 py-3 bg-indigo-600 text-white rounded-md font-semibold hover:bg-indigo-700">
                            Connectez-vous pour réserver
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</x-app-layout>