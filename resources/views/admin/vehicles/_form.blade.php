@php
    $v = $vehicle ?? null;
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

    <div>
        <label class="block text-sm font-medium mb-1">Immatriculation *</label>
        <input type="text" name="registration_number" required
               value="{{ old('registration_number', $v->registration_number ?? '') }}"
               placeholder="AB-123-CD"
               class="w-full rounded-md border-gray-300">
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Marque *</label>
        <input type="text" name="brand" required
               value="{{ old('brand', $v->brand ?? '') }}"
               placeholder="Toyota, BMW..."
               class="w-full rounded-md border-gray-300">
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Modèle *</label>
        <input type="text" name="model" required
               value="{{ old('model', $v->model ?? '') }}"
               placeholder="Corolla, X5..."
               class="w-full rounded-md border-gray-300">
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Année *</label>
        <input type="number" name="year" required min="1990" max="{{ date('Y') + 1 }}"
               value="{{ old('year', $v->year ?? date('Y')) }}"
               class="w-full rounded-md border-gray-300">
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Couleur</label>
        <input type="text" name="color"
               value="{{ old('color', $v->color ?? '') }}"
               placeholder="Blanc, Noir..."
               class="w-full rounded-md border-gray-300">
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Carburant *</label>
        <select name="fuel_type" required class="w-full rounded-md border-gray-300">
            @foreach (['petrol' => 'Essence', 'diesel' => 'Diesel', 'electric' => 'Électrique', 'hybrid' => 'Hybride'] as $value => $label)
                <option value="{{ $value }}" @selected(old('fuel_type', $v->fuel_type ?? '') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Transmission *</label>
        <select name="transmission" required class="w-full rounded-md border-gray-300">
            <option value="manual"    @selected(old('transmission', $v->transmission ?? '') === 'manual')>Manuelle</option>
            <option value="automatic" @selected(old('transmission', $v->transmission ?? '') === 'automatic')>Automatique</option>
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Nombre de sièges *</label>
        <input type="number" name="seats" required min="2" max="9"
               value="{{ old('seats', $v->seats ?? 5) }}"
               class="w-full rounded-md border-gray-300">
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Nombre de portes *</label>
        <input type="number" name="doors" required min="2" max="5"
               value="{{ old('doors', $v->doors ?? 4) }}"
               class="w-full rounded-md border-gray-300">
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Prix par jour (MAD) *</label>
        <input type="number" step="0.01" name="daily_rate" required min="0"
               value="{{ old('daily_rate', $v->daily_rate ?? '') }}"
               class="w-full rounded-md border-gray-300">
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Kilométrage *</label>
        <input type="number" name="mileage" required min="0"
               value="{{ old('mileage', $v->mileage ?? 0) }}"
               class="w-full rounded-md border-gray-300">
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Statut *</label>
        <select name="status" required class="w-full rounded-md border-gray-300">
            @foreach (['available' => 'Disponible', 'rented' => 'Loué', 'maintenance' => 'Maintenance', 'out_of_service' => 'Hors service'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $v->status ?? 'available') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium mb-1">Description</label>
        <textarea name="description" rows="3"
                  placeholder="Équipements, options, remarques..."
                  class="w-full rounded-md border-gray-300">{{ old('description', $v->description ?? '') }}</textarea>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium mb-1">Image</label>

        @if ($v && $v->image_path)
            <div class="mb-3 flex items-center gap-4">
                <img src="{{ asset('storage/' . $v->image_path) }}"
                     class="h-24 w-32 object-cover rounded border" alt="{{ $v->brand }}">
                <p class="text-xs text-gray-500">
                    Image actuelle. Sélectionnez un nouveau fichier pour la remplacer.
                </p>
            </div>
        @endif

        <input type="file" name="image" accept="image/*"
               class="w-full rounded-md border-gray-300">
        <p class="text-xs text-gray-500 mt-1">
            Formats : JPG, PNG, WEBP. Taille max : 2 Mo.
        </p>
    </div>
</div>