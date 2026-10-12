<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier : ' . $user->name) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow p-6">

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium mb-1">Nom *</label>
                        <input type="text" name="name" required
                               value="{{ old('name', $user->name) }}"
                               class="w-full rounded-md border-gray-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Email *</label>
                        <input type="email" name="email" required
                               value="{{ old('email', $user->email) }}"
                               class="w-full rounded-md border-gray-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Téléphone</label>
                        <input type="text" name="phone"
                               value="{{ old('phone', $user->phone) }}"
                               class="w-full rounded-md border-gray-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Adresse</label>
                        <input type="text" name="address"
                               value="{{ old('address', $user->address) }}"
                               class="w-full rounded-md border-gray-300">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Ville</label>
                            <input type="text" name="city"
                                   value="{{ old('city', $user->city) }}"
                                   class="w-full rounded-md border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Pays</label>
                            <input type="text" name="country"
                                   value="{{ old('country', $user->country) }}"
                                   class="w-full rounded-md border-gray-300">
                        </div>
                    </div>

                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1"
                               @checked(old('is_active', $user->is_active))
                               class="rounded border-gray-300">
                        <span class="text-sm">Compte actif</span>
                    </label>

                    <div class="flex justify-end gap-3 pt-4">
                        <a href="{{ route('admin.users.show', $user) }}"
                           class="px-4 py-2 border rounded-md text-sm">Annuler</a>
                        <button class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>