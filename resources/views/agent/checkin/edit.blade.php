<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Check-in — Contrat ' . $contract->contract_number) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if ($errors->any())
                <div class="p-4 bg-red-100 text-red-800 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Client & Vehicle Summary --}}
            <div class="bg-white rounded-lg shadow p-6 space-y-4">
                <div>
                    <h3 class="font-bold text-lg mb-2">Client</h3>
                    <p>{{ $contract->client->name }}</p>
                    <p class="text-sm text-gray-500">{{ $contract->client->phone }}</p>
                </div>

                <div>
                    <h3 class="font-bold text-lg mb-2">Véhicule</h3>
                    <p>
                        {{ $contract->reservation->vehicle->brand }}
                        {{ $contract->reservation->vehicle->model }}
                        <span class="text-gray-500 font-mono text-sm">
                            ({{ $contract->reservation->vehicle->registration_number }})
                        </span>
                    </p>
                </div>
            </div>

            {{-- Check-in Summary --}}
            <div class="bg-blue-50 border-l-4 border-blue-400 p-6 rounded">
                <h3 class="font-bold text-lg mb-3">État au départ (check-in)</h3>
                <dl class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <dt class="text-gray-600">Signé le</dt>
                        <dd class="font-medium">{{ $contract->signed_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-600">Kilométrage</dt>
                        <dd class="font-medium">{{ number_format($contract->pickup_mileage) }} km</dd>
                    </div>
                    <div>
                        <dt class="text-gray-600">Carburant</dt>
                        <dd class="font-medium">{{ ucfirst($contract->pickup_fuel_level) }}</dd>
                    </div>
                    <div class="col-span-2">
                        <dt class="text-gray-600">Observations</dt>
                        <dd class="font-medium">
                            {{ $contract->pickup_condition ?: 'Aucune' }}
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Check-in Form --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-bold text-lg mb-4">État au retour (check-in)</h3>

                <form method="POST" action="{{ route('agent.checkin.update', $contract) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Kilométrage au retour *</label>
                            <input type="number" name="return_mileage" required
                                   min="{{ $contract->pickup_mileage }}"
                                   value="{{ old('return_mileage') }}"
                                   class="w-full rounded-md border-gray-300">
                            <p class="text-xs text-gray-500 mt-1">
                                Minimum : {{ number_format($contract->pickup_mileage) }} km
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Niveau de carburant *</label>
                            <select name="return_fuel_level" required class="w-full rounded-md border-gray-300">
                                <option value="empty">Vide</option>
                                <option value="quarter">1/4</option>
                                <option value="half" selected>1/2</option>
                                <option value="three_quarters">3/4</option>
                                <option value="full">Plein</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">État / observations</label>
                        <textarea name="return_condition" rows="3"
                                  class="w-full rounded-md border-gray-300"
                                  placeholder="Dommages constatés, propreté...">{{ old('return_condition') }}</textarea>
                    </div>

                    <div class="border-t pt-4">
                        <h4 class="font-medium mb-3">Frais supplémentaires (facultatif)</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Montant (MAD)</label>
                                <input type="number" step="0.01" name="extra_charges"
                                       min="0" value="{{ old('extra_charges', 0) }}"
                                       class="w-full rounded-md border-gray-300">
                                <p class="text-xs text-gray-500 mt-1">
                                    Ex: carburant manquant, retard, dommages
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Motif</label>
                                <input type="text" name="extra_charges_notes"
                                       value="{{ old('extra_charges_notes') }}"
                                       class="w-full rounded-md border-gray-300">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <a href="{{ route('agent.checkin.index') }}"
                           class="px-4 py-2 border rounded-md text-sm">Annuler</a>
                        <button class="px-6 py-2 bg-indigo-600 text-black rounded-md text-sm hover:bg-indigo-700"
                                onclick="return confirm('Confirmer le retour du véhicule ? Une facture sera automatiquement générée.')">
                            Valider le retour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>