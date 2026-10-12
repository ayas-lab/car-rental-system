<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Toutes les réservations') }}
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

            <form method="GET" class="bg-white rounded-lg shadow p-4 mb-6 flex gap-3">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Référence, client, véhicule..."
                       class="flex-1 rounded-md border-gray-300">
                <select name="status" class="rounded-md border-gray-300">
                    <option value="">Tous les statuts</option>
                    @foreach (['pending', 'confirmed', 'active', 'completed', 'cancelled'] as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
                <button class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">Filtrer</button>
                <a href="{{ route('admin.reservations.index') }}"
                   class="px-4 py-2 border rounded-md text-sm">Reset</a>
            </form>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Réf.</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Véhicule</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dates</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($reservations as $res)
                            <tr>
                                <td class="px-4 py-3 text-sm font-mono">{{ $res->reference }}</td>
                                <td class="px-4 py-3 text-sm">{{ $res->user->name }}</td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $res->vehicle->brand }} {{ $res->vehicle->model }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $res->start_date->format('d/m') }}
                                    → {{ $res->end_date->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-3 text-sm font-semibold">
                                    {{ number_format($res->total_price, 2) }} MAD
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $colors = [
                                            'pending'   => 'bg-yellow-100 text-yellow-800',
                                            'confirmed' => 'bg-blue-100 text-blue-800',
                                            'active'    => 'bg-green-100 text-green-800',
                                            'completed' => 'bg-gray-100 text-gray-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 text-xs rounded-full {{ $colors[$res->status] }}">
                                        {{ ucfirst($res->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.reservations.show', $res) }}"
                                       class="text-indigo-600 hover:underline text-sm">Voir</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    Aucune réservation trouvée.
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