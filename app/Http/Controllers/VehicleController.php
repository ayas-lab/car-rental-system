<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $query = Vehicle::query()
            ->whereIn('status', ['available', 'rented']); // show both, badge handles the display

        // Search by brand/model
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%");
            });
        }

        // Filter by brand
        if ($brand = $request->input('brand')) {
            $query->where('brand', $brand);
        }

        // Filter by fuel type
        if ($fuel = $request->input('fuel_type')) {
            $query->where('fuel_type', $fuel);
        }

        // Filter by transmission
        if ($trans = $request->input('transmission')) {
            $query->where('transmission', $trans);
        }

        // Filter by max price
        if ($maxPrice = $request->input('max_price')) {
            $query->where('daily_rate', '<=', $maxPrice);
        }

        // Date-range availability filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->input('start_date');
            $endDate   = $request->input('end_date');

            // Exclude vehicles already booked in this range
            $query->whereDoesntHave('reservations', function ($q) use ($startDate, $endDate) {
                $q->whereIn('status', ['pending', 'confirmed', 'active'])
                  ->where(function ($sub) use ($startDate, $endDate) {
                      $sub->whereBetween('start_date', [$startDate, $endDate])
                          ->orWhereBetween('end_date', [$startDate, $endDate])
                          ->orWhere(function ($inner) use ($startDate, $endDate) {
                              $inner->where('start_date', '<=', $startDate)
                                    ->where('end_date', '>=', $endDate);
                          });
                  });
            });
        }

        $vehicles = $query->latest()->paginate(12)->withQueryString();

        // For the brand filter dropdown
        $brands = Vehicle::distinct()->pluck('brand')->sort();

        return view('vehicles.index', compact('vehicles', 'brands'));
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load('reservations');

        return view('vehicles.show', compact('vehicle'));
    }
}