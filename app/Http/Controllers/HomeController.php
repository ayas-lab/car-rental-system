<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;

class HomeController extends Controller
{
    public function index()
    {
        $featuredVehicles = Vehicle::where('status', 'available')
            ->take(6)
            ->get();

        return view('welcome', compact('featuredVehicles'));
    }
}