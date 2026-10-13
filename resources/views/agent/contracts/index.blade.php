<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Contrats') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Filter --}}
            <form method="GET" class="bg-white rounded-lg shadow p-4 flex gap-3">
                <select name="status" class="flex-1 rounded-md border-gray-300">
                    <option value="">Tous les statuts</option>
                    <option value="active"    @selected(request('status') === 'active')>Actifs</option>
                    <option value="completed" @selected(request('status') === 'completed')>Terminés</option>
                    <option value="overdue"   @selected(request('status') === 'overdue')>En retard</option>
                </select>
                <button class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">
                    Filtrer
                </button>
                <a href="{{ route('agent.contracts.index') }}"
                   class="px-4 py-2 border rounded-md text-sm">
                    Réinitialiser
                </a>
            </form>

            {{-- Contracts Table --}}
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">N° contrat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Véhicule</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Signé le</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Retourné le</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($contracts as $contract)
                            <tr>
                                <td class="px-6 py-4 text-sm font-mono">
                                    {{ $contract->contract_number }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    {{ $contract->client->name }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    {{ $contract->reservation->vehicle->brand }}
                                    {{ $contract->reservation->vehicle->model }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $contract->signed_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $contract->returned_at?->format('d/m/Y') ?? '—' }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $colors = [
                                            'active'    => 'bg-green-100 text-green-800',
                                            'completed' => 'bg-gray-100 text-gray-800',
                                            'overdue'   => 'bg-red-100 text-red-800',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 text-xs rounded-full {{ $colors[$contract->status] ?? 'bg-gray-100' }}">
                                        {{ ucfirst($contract->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('agent.contracts.show', $contract) }}"
                                       class="text-indigo-600 hover:underline text-sm">Voir</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    Aucun contrat trouvé.
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