<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Check-out — ' . $reservation->reference) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="font-bold text-lg mb-2">Client</h3>
                <p>{{ $reservation->user->name }}</p>
                <p class="text-sm text-gray-500">{{ $reservation->user->phone }}</p>

                <h3 class="font-bold text-lg mt-4 mb-2">Véhicule</h3>
                <p>{{ $reservation->vehicle->brand }} {{ $reservation->vehicle->model }}
                   ({{ $reservation->vehicle->registration_number }})</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <form method="POST" action="{{ route('agent.checkout.store', $reservation) }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Kilométrage au départ *</label>
                            <input type="number" name="pickup_mileage" required min="0"
                                   value="{{ old('pickup_mileage', $reservation->vehicle->mileage) }}"
                                   class="w-full rounded-md border-gray-300">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Niveau de carburant *</label>
                            <select name="pickup_fuel_level" required class="w-full rounded-md border-gray-300">
                                <option value="empty">Vide</option>
                                <option value="quarter">1/4</option>
                                <option value="half" selected>1/2</option>
                                <option value="three_quarters">3/4</option>
                                <option value="full">Plein</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium mb-1">État / observations</label>
                            <textarea name="pickup_condition" rows="3"
                                      class="w-full rounded-md border-gray-300">{{ old('pickup_condition') }}</textarea>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <a href="{{ route('agent.checkout.index') }}"
                           class="px-4 py-2 border rounded-md text-sm">Annuler</a>
                        <button class="px-6 py-2 bg-indigo-600 text-black rounded-md text-sm hover:bg-indigo-700">
                            Signer le contrat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>