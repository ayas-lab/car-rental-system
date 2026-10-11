<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Réserver un véhicule') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <div class="flex gap-4">
                    @if ($vehicle->image_path)
                        <img src="{{ asset('storage/' . $vehicle->image_path) }}"
                             class="w-32 h-24 object-cover rounded">
                    @endif
                    <div>
                        <h3 class="font-bold text-lg">{{ $vehicle->brand }} {{ $vehicle->model }}</h3>
                        <p class="text-sm text-gray-500">{{ $vehicle->registration_number }}</p>
                        <p class="text-indigo-600 font-bold mt-1">
                            {{ number_format($vehicle->daily_rate, 2) }} MAD / jour
                        </p>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-lg shadow p-6" x-data="bookingForm({{ $vehicle->daily_rate }})">
                <form method="POST" action="{{ route('client.reservations.store') }}">
                    @csrf
                    <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Date de début *</label>
                            <input type="date" name="start_date" required
                                   x-model="startDate" @change="calculate()"
                                   min="{{ date('Y-m-d') }}"
                                   value="{{ old('start_date') }}"
                                   class="w-full rounded-md border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Date de fin *</label>
                            <input type="date" name="end_date" required
                                   x-model="endDate" @change="calculate()"
                                   min="{{ date('Y-m-d') }}"
                                   value="{{ old('end_date') }}"
                                   class="w-full rounded-md border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Lieu de prise en charge</label>
                            <input type="text" name="pickup_location"
                                   value="{{ old('pickup_location') }}"
                                   class="w-full rounded-md border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Lieu de restitution</label>
                            <input type="text" name="dropoff_location"
                                   value="{{ old('dropoff_location') }}"
                                   class="w-full rounded-md border-gray-300">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Notes (facultatif)</label>
                        <textarea name="notes" rows="2"
                                  class="w-full rounded-md border-gray-300">{{ old('notes') }}</textarea>
                    </div>

                    {{-- Live pricing preview --}}
                    <div class="border-t pt-4 mb-4 text-sm space-y-1" x-show="days > 0">
                        <div class="flex justify-between">
                            <span x-text="days + ' jour(s) × ' + rate.toFixed(2) + ' MAD'"></span>
                            <span x-text="subtotal.toFixed(2) + ' MAD'"></span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>TVA (20%)</span>
                            <span x-text="tax.toFixed(2) + ' MAD'"></span>
                        </div>
                        <div class="flex justify-between font-bold text-lg text-indigo-700 pt-2 border-t">
                            <span>Total</span>
                            <span x-text="total.toFixed(2) + ' MAD'"></span>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('vehicles.show', $vehicle) }}"
                           class="px-4 py-2 border rounded-md text-sm">Annuler</a>
                        <button class="px-6 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">
                            Confirmer la réservation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function bookingForm(rate) {
            return {
                rate: parseFloat(rate),
                startDate: '',
                endDate: '',
                days: 0,
                subtotal: 0,
                tax: 0,
                total: 0,
                calculate() {
                    if (!this.startDate || !this.endDate) return;
                    const start = new Date(this.startDate);
                    const end   = new Date(this.endDate);
                    const diff  = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
                    this.days     = diff > 0 ? diff : 0;
                    this.subtotal = this.days * this.rate;
                    this.tax      = this.subtotal * 0.20;
                    this.total    = this.subtotal + this.tax;
                }
            }
        }
    </script>
</x-app-layout>