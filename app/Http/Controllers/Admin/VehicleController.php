<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VehicleController extends Controller
{
    /**
     * List all vehicles with search and status filter.
     */
    public function index(Request $request)
    {
        $query = Vehicle::query();

        // Search by brand, model, or registration
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('registration_number', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $vehicles = $query->latest()->paginate(12)->withQueryString();

        return view('admin.vehicles.index', compact('vehicles'));
    }

    /**
     * Show the create form.
     */
    public function create()
    {
        return view('admin.vehicles.create');
    }

    /**
     * Store a new vehicle.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'registration_number' => 'required|string|unique:vehicles|max:20',
            'brand'               => 'required|string|max:50',
            'model'               => 'required|string|max:50',
            'year'                => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'color'               => 'nullable|string|max:30',
            'fuel_type'           => 'required|in:petrol,diesel,electric,hybrid',
            'transmission'        => 'required|in:manual,automatic',
            'seats'               => 'required|integer|min:2|max:9',
            'doors'               => 'required|integer|min:2|max:5',
            'daily_rate'          => 'required|numeric|min:0',
            'mileage'             => 'required|integer|min:0',
            'status'              => 'required|in:available,rented,maintenance,out_of_service',
            'description'         => 'nullable|string|max:1000',
            'image'               => 'nullable|image|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('vehicles', 'public');
        }

        Vehicle::create($validated);

        return redirect()
            ->route('admin.vehicles.index')
            ->with('success', 'Véhicule créé avec succès.');
    }

    /**
     * Show a single vehicle with reservations history.
     */
    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['reservations.user', 'reservations.contract']);

        return view('admin.vehicles.show', compact('vehicle'));
    }

    /**
     * Show the edit form.
     */
    public function edit(Vehicle $vehicle)
    {
        return view('admin.vehicles.edit', compact('vehicle'));
    }

    /**
     * Update an existing vehicle.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'registration_number' => 'required|string|max:20|unique:vehicles,registration_number,' . $vehicle->id,
            'brand'               => 'required|string|max:50',
            'model'               => 'required|string|max:50',
            'year'                => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'color'               => 'nullable|string|max:30',
            'fuel_type'           => 'required|in:petrol,diesel,electric,hybrid',
            'transmission'        => 'required|in:manual,automatic',
            'seats'               => 'required|integer|min:2|max:9',
            'doors'               => 'required|integer|min:2|max:5',
            'daily_rate'          => 'required|numeric|min:0',
            'mileage'             => 'required|integer|min:0',
            'status'              => 'required|in:available,rented,maintenance,out_of_service',
            'description'         => 'nullable|string|max:1000',
            'image'               => 'nullable|image|max:2048',
        ]);

        // Replace image if a new one is uploaded
        if ($request->hasFile('image')) {
            if ($vehicle->image_path) {
                Storage::disk('public')->delete($vehicle->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('vehicles', 'public');
        }

        $vehicle->update($validated);

        return redirect()
            ->route('admin.vehicles.index')
            ->with('success', 'Véhicule mis à jour avec succès.');
    }

    /**
     * Soft-delete a vehicle.
     */
    public function destroy(Vehicle $vehicle)
    {
        if ($vehicle->image_path) {
            Storage::disk('public')->delete($vehicle->image_path);
        }

        $vehicle->delete();

        return redirect()
            ->route('admin.vehicles.index')
            ->with('success', 'Véhicule supprimé.');
    }
}