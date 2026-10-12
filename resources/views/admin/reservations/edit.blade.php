<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier : ' . $reservation->reference) }}
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

                <form method="POST" action="{{ route('admin.reservations.update', $reservation) }}"
                      class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Date de début *</label>
                            <input type="date" name="start_date" required
                                   value="{{ old('start_date', $reservation->start_date->format('Y-m-d')) }}"
                                   class="w-full rounded-md border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Date de fin *</label>
                            <input type="date" name="end_date" required
                                   value="{{ old('end_date', $reservation->end_date->format('Y-m-d')) }}"
                                   class="w-full rounded-md border-gray-300">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Lieu de prise en charge</label>
                        <input type="text" name="pickup_location"
                               value="{{ old('pickup_location', $reservation->pickup_location) }}"
                               class="w-full rounded-md border-gray-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Lieu de restitution</label>
                        <input type="text" name="dropoff_location"
                               value="{{ old('dropoff_location', $reservation->dropoff_location) }}"
                               class="w-full rounded-md border-gray-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Notes</label>
                        <textarea name="notes" rows="3"
                                  class="w-full rounded-md border-gray-300">{{ old('notes', $reservation->notes) }}</textarea>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.reservations.show', $reservation) }}"
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