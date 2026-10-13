<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Check-out — Remises à effectuer') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
            @endif

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Référence</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Véhicule</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Début</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($reservations as $reservation)
                            <tr>
                                <td class="px-6 py-4 text-sm font-mono">{{ $reservation->reference }}</td>
                                <td class="px-6 py-4 text-sm">
                                    {{ $reservation->user->name }}<br>
                                    <span class="text-gray-500 text-xs">{{ $reservation->user->phone }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    {{ $reservation->vehicle->brand }} {{ $reservation->vehicle->model }}<br>
                                    <span class="text-gray-500 text-xs font-mono">
                                        {{ $reservation->vehicle->registration_number }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    {{ $reservation->start_date->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('agent.checkout.create', $reservation) }}"
                                       class="px-4 py-2 bg-indigo-600 text-black rounded-md text-sm hover:bg-indigo-700">
                                        Effectuer le check-out
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    Aucune remise prévue pour les prochains jours.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">{{ $reservations->links() }}</div>
        </div>
    </div>
</x-app-layout>