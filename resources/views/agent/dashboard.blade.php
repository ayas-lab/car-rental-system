<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Espace Agent') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-2xl font-bold mb-2">
                    Bonjour {{ auth()->user()->name }}
                </h3>
                <p class="text-gray-600">Voici vos tâches du jour.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-500">Remises à effectuer</p>
                    <p class="text-3xl font-bold text-blue-600 mt-1">
                        {{ $stats['pending_checkouts'] }}
                    </p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-500">Contrats actifs</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">
                        {{ $stats['active_contracts'] }}
                    </p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-500">Retours en retard</p>
                    <p class="text-3xl font-bold text-red-600 mt-1">
                        {{ $stats['overdue_returns'] }}
                    </p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-500">Véhicules disponibles</p>
                    <p class="text-3xl font-bold text-indigo-600 mt-1">
                        {{ $stats['vehicles_available'] }}
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-bold text-lg mb-4">Actions rapides</h3>
                <div class="flex gap-3 flex-wrap">
                    <a href="{{ route('agent.checkout.index') }}"
                       class="px-4 py-2 bg-indigo-600 text-black rounded-md text-sm">
                        Effectuer une remise
                    </a>
                    <a href="{{ route('agent.checkin.index') }}"
                       class="px-4 py-2 bg-green-600 text-black rounded-md text-sm">
                        Effectuer un retour
                    </a>
                    <a href="{{ route('agent.contracts.index') }}"
                       class="px-4 py-2 bg-gray-100 rounded-md text-sm">
                        Voir les contrats
                    </a>
                </div>
            </div>

            @if ($upcomingPickups->isNotEmpty())
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-bold text-lg mb-4">Prochaines remises</h3>
                    <ul class="divide-y divide-gray-100">
                        @foreach ($upcomingPickups as $pickup)
                            <li class="py-3 flex justify-between items-center">
                                <div>
                                    <p class="font-mono text-sm text-gray-500">
                                        {{ $pickup->reference }}
                                    </p>
                                    <p class="font-medium">
                                        {{ $pickup->user->name }} —
                                        {{ $pickup->vehicle->brand }} {{ $pickup->vehicle->model }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $pickup->start_date->format('d/m/Y') }}
                                    </p>
                                </div>
                                <a href="{{ route('agent.checkout.create', $pickup) }}"
                                   class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm">
                                    Remettre
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>