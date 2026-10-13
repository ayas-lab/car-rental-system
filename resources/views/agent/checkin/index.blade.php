<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Check-in — Retours à traiter') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="p-4 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
            @endif

            {{-- Summary Cards --}}
            @php
                $activeCount   = $contracts->getCollection()->where('status', 'active')->count();
                $overdueCount  = $contracts->getCollection()->where('status', 'overdue')->count();
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-500">Contrats en cours</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">{{ $activeCount }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-500">Retours en retard</p>
                    <p class="text-3xl font-bold text-red-600 mt-1">{{ $overdueCount }}</p>
                </div>
            </div>

            {{-- Contracts Table --}}
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contrat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Véhicule</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sortie</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Retour prévu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($contracts as $contract)
                            @php
                                $reservation  = $contract->reservation;
                                $expectedDate = $reservation->end_date;
                                $isOverdue    = $expectedDate->isPast();
                            @endphp
                            <tr>
                                <td class="px-6 py-4 text-sm font-mono">
                                    {{ $contract->contract_number }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    {{ $contract->client->name }}<br>
                                    <span class="text-gray-500 text-xs">{{ $contract->client->phone }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    {{ $reservation->vehicle->brand }} {{ $reservation->vehicle->model }}<br>
                                    <span class="text-gray-500 text-xs font-mono">
                                        {{ $reservation->vehicle->registration_number }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    {{ $contract->signed_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm {{ $isOverdue ? 'text-red-600 font-semibold' : '' }}">
                                    {{ $expectedDate->format('d/m/Y') }}
                                    @if ($isOverdue)
                                        <br><span class="text-xs">⚠️ En retard</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full
                                        {{ $contract->status === 'overdue' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ ucfirst($contract->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('agent.checkin.edit', $contract) }}"
                                       class="px-4 py-2 bg-indigo-600 text-black rounded-md text-sm hover:bg-indigo-700">
                                        Traiter le retour
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    Aucun contrat actif à traiter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $contracts->links() }}
            </div>
        </div>
    </div>
</x-app-layout>