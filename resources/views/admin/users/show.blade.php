<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Utilisateur : ' . $user->name) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="p-4 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
            @endif

            {{-- Profile Card --}}
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="font-bold text-lg">Profil</h3>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.users.edit', $user) }}"
                           class="px-3 py-1 bg-gray-100 text-sm rounded-md hover:bg-gray-200">
                            Modifier
                        </a>
                        @if ($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}">
                                @csrf
                                @method('PATCH')
                                <button class="px-3 py-1 text-sm rounded-md
                                    {{ $user->is_active ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                    {{ $user->is_active ? 'Désactiver' : 'Activer' }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <dl class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <dt class="text-sm text-gray-500">Nom</dt>
                        <dd class="font-medium">{{ $user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Email</dt>
                        <dd class="font-medium">{{ $user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Téléphone</dt>
                        <dd class="font-medium">{{ $user->phone ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Ville</dt>
                        <dd class="font-medium">{{ $user->city ?? '—' }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Role Management --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-bold text-lg mb-4">Rôle</h3>
                <form method="POST" action="{{ route('admin.users.role', $user) }}"
                      class="flex gap-3 items-end">
                    @csrf
                    @method('PATCH')

                    <div class="flex-1">
                        <label class="block text-sm font-medium mb-1">Rôle actuel</label>
                        <select name="role" class="w-full rounded-md border-gray-300">
                            @foreach (['client', 'agent', 'admin'] as $role)
                                <option value="{{ $role }}"
                                    @selected($user->hasRole($role))>
                                    {{ ucfirst($role) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm">
                        Mettre à jour
                    </button>
                </form>
            </div>

            {{-- Reservations --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-bold text-lg mb-4">
                    Réservations ({{ $user->reservations->count() }})
                </h3>
                @if ($user->reservations->isEmpty())
                    <p class="text-gray-500 text-sm">Aucune réservation.</p>
                @else
                    <ul class="divide-y divide-gray-100">
                        @foreach ($user->reservations->take(5) as $res)
                            <li class="py-2 flex justify-between text-sm">
                                <span class="font-mono">{{ $res->reference }}</span>
                                <span>{{ $res->vehicle->brand }} {{ $res->vehicle->model }}</span>
                                <span class="text-gray-500">{{ $res->start_date->format('d/m/Y') }}</span>
                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100">{{ $res->status }}</span>
                            </li>
                        @endforeach
                    </ul>
                    @if ($user->reservations->count() > 5)
                        <p class="text-sm text-gray-500 mt-2">... et {{ $user->reservations->count() - 5 }} autres.</p>
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-app-layout>