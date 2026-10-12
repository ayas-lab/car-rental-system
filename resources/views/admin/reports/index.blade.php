<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Rapports & Statistiques') }}
            </h2>
            <form method="GET" class="flex gap-2">
                <select name="period" onchange="this.form.submit()"
                        class="rounded-md border-gray-300 text-sm">
                    <option value="7"  @selected($period == 7)>7 derniers jours</option>
                    <option value="30" @selected($period == 30)>30 derniers jours</option>
                    <option value="90" @selected($period == 90)>90 derniers jours</option>
                </select>
                <a href="{{ route('admin.reports.export', ['period' => $period]) }}"
                   class="px-3 py-2 bg-gray-800 text-white rounded-md text-sm">
                    Export CSV
                </a>
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- KPI Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @php
                    $cards = [
                        ['label' => 'Revenus encaissés',  'value' => number_format($kpis['total_revenue'], 0) . ' MAD', 'color' => 'green'],
                        ['label' => 'Revenus en attente', 'value' => number_format($kpis['pending_revenue'], 0) . ' MAD', 'color' => 'yellow'],
                        ['label' => 'Réservations',       'value' => $kpis['total_reservations'], 'color' => 'blue'],
                        ['label' => 'Locations actives',  'value' => $kpis['active_rentals'], 'color' => 'indigo'],
                        ['label' => 'Flotte totale',      'value' => $kpis['fleet_size'], 'color' => 'gray'],
                        ['label' => 'Taux utilisation',   'value' => $kpis['utilization_rate'] . '%', 'color' => 'purple'],
                    ];
                @endphp

                @foreach ($cards as $card)
                    <div class="bg-white rounded-lg shadow p-4">
                        <p class="text-xs text-gray-500 uppercase">{{ $card['label'] }}</p>
                        <p class="text-2xl font-bold text-{{ $card['color'] }}-600 mt-1">
                            {{ $card['value'] }}
                        </p>
                    </div>
                @endforeach
            </div>

            {{-- Revenue Chart --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-bold text-lg mb-4">Revenus par jour</h3>
                <canvas id="revenueChart" height="80"></canvas>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Top Vehicles --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-bold text-lg mb-4">Top 5 véhicules</h3>
                    <ul class="divide-y divide-gray-100">
                        @forelse ($topVehicles as $v)
                            <li class="py-2 flex justify-between">
                                <span>{{ $v->brand }} {{ $v->model }}</span>
                                <span class="text-sm text-gray-500">
                                    {{ $v->reservations_count }} réservations
                                </span>
                            </li>
                        @empty
                            <li class="py-2 text-gray-500 text-sm">Aucune donnée.</li>
                        @endforelse
                    </ul>
                </div>

                {{-- Top Clients --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-bold text-lg mb-4">Top 5 clients</h3>
                    <ul class="divide-y divide-gray-100">
                        @forelse ($topClients as $c)
                            <li class="py-2 flex justify-between">
                                <span>{{ $c->name }}</span>
                                <span class="text-sm font-medium">
                                    {{ number_format($c->total_paid ?? 0, 0) }} MAD
                                </span>
                            </li>
                        @empty
                            <li class="py-2 text-gray-500 text-sm">Aucune donnée.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            {{-- Status Breakdown --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-bold text-lg mb-4">Répartition par statut</h3>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    @foreach (['pending', 'confirmed', 'active', 'completed', 'cancelled'] as $status)
                        <div class="text-center">
                            <p class="text-3xl font-bold text-indigo-600">
                                {{ $statusBreakdown[$status] ?? 0 }}
                            </p>
                            <p class="text-sm text-gray-500 mt-1">{{ ucfirst($status) }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('revenueChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($revenueChart->pluck('date')),
                datasets: [{
                    label: 'Revenus (MAD)',
                    data: @json($revenueChart->pluck('revenue')),
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    fill: true,
                    tension: 0.3,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
</x-app-layout>