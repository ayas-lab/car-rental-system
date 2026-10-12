<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion des utilisateurs') }}
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

            {{-- Filters --}}
            <form method="GET" class="bg-white rounded-lg shadow p-4 mb-6 flex gap-3">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Nom, email ou téléphone..."
                       class="flex-1 rounded-md border-gray-300">
                <select name="role" class="rounded-md border-gray-300">
                    <option value="">Tous les rôles</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role }}" @selected(request('role') === $role)>
                            {{ ucfirst($role) }}
                        </option>
                    @endforeach
                </select>
                <button class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">Filtrer</button>
                <a href="{{ route('admin.users.index') }}"
                   class="px-4 py-2 border rounded-md text-sm">Reset</a>
            </form>

            {{-- Users Table --}}
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rôle</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Inscrit le</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($users as $user)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium">
                                    {{ $user->name }}
                                    @if ($user->id === auth()->id())
                                        <span class="text-xs text-gray-400">(vous)</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                                <td class="px-6 py-4">
                                    @foreach ($user->roles as $role)
                                        @php
                                            $roleColors = [
                                                'admin'  => 'bg-purple-100 text-purple-800',
                                                'agent'  => 'bg-blue-100 text-blue-800',
                                                'client' => 'bg-green-100 text-green-800',
                                            ];
                                        @endphp
                                        <span class="px-2 py-1 text-xs rounded-full {{ $roleColors[$role->name] ?? 'bg-gray-100' }}">
                                            {{ ucfirst($role->name) }}
                                        </span>
                                    @endforeach
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full
                                        {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $user->is_active ? 'Actif' : 'Inactif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $user->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="{{ route('admin.users.show', $user) }}"
                                       class="text-indigo-600 hover:underline text-sm">Détails</a>
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                       class="text-gray-600 hover:underline text-sm">Modifier</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    Aucun utilisateur trouvé.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">{{ $users->links() }}</div>
        </div>
    </div>
</x-app-layout>