<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mon espace') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Welcome --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-2xl font-bold mb-2">
                    Bonjour {{ auth()->user()->name }}
                </h3>
                <p class="text-gray-600">Bienvenue sur votre espace personnel.</p>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-500">Réservations totales</p>
                    <p class="text-3xl font-bold text-indigo-600 mt-1">
                        {{ $stats['total_reservations'] }}
                    </p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-500">Réservations actives</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">
                        {{ $stats['active_reservations'] }}
                    </p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-500">Factures impayées</p>
                    <p class="text-3xl font-bold text-red-600 mt-1">
                        {{ $stats['unpaid_invoices'] }}
                    </p>
                </div>
            </div>

            {{-- Upcoming Reservation --}}
            @if ($upcomingReservation)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-bold text-lg mb-4">Prochaine réservation</h3>
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-mono text-sm text-gray-500">
                                {{ $upcomingReservation->reference }}
                            </p>
                            <p class="font-bold text-lg">
                                {{ $upcomingReservation->vehicle->brand }}
                                {{ $upcomingReservation->vehicle->model }}
                            </p>
                            <p class="text-sm text-gray-600 mt-1">
                                Du {{ $upcomingReservation->start_date->format('d/m/Y') }}
                                au {{ $upcomingReservation->end_date->format('d/m/Y') }}
                            </p>
                        </div>
                        <a href="{{ route('client.reservations.show', $upcomingReservation) }}"
                           class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm">
                            Voir
                        </a>
                    </div>
                </div>
            @endif

            {{-- Quick Actions --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-bold text-lg mb-4">Actions rapides</h3>
                <div class="flex gap-3 flex-wrap">
                    <a href="{{ route('vehicles.index') }}"
                       class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm">
                        Parcourir les véhicules
                    </a>
                    <a href="{{ route('client.reservations.index') }}"
                       class="px-4 py-2 bg-gray-100 rounded-md text-sm">
                        Mes réservations
                    </a>
                    <a href="{{ route('client.invoices.index') }}"
                       class="px-4 py-2 bg-gray-100 rounded-md text-sm">
                        Mes factures
                    </a>
                </div>
            </div>

            {{-- Recent Invoices --}}
            @if ($recentInvoices->isNotEmpty())
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-bold text-lg mb-4">Factures récentes</h3>
                    <ul class="divide-y divide-gray-100">
                        @foreach ($recentInvoices as $invoice)
                            <li class="py-3 flex justify-between items-center">
                                <div>
                                    <p class="font-mono text-sm">{{ $invoice->invoice_number }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $invoice->issued_at->format('d/m/Y') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold">
                                        {{ number_format($invoice->total, 2) }} MAD
                                    </p>
                                    <span class="text-xs px-2 py-1 rounded-full
                                        {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>