<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Vehicle;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_vehicles'    => Vehicle::count(),
            'available'         => Vehicle::where('status', 'available')->count(),
            'rented'            => Vehicle::where('status', 'rented')->count(),
            'total_clients'     => User::role('client')->count(),
            'total_agents'      => User::role('agent')->count(),
            'active_reservations' => Reservation::whereIn('status', ['pending', 'confirmed', 'active'])->count(),
            'unpaid_invoices'   => Invoice::where('status', 'unpaid')->count(),
            'total_revenue'     => Invoice::where('status', 'paid')->sum('total'),
        ];

        $recentReservations = Reservation::with(['user', 'vehicle'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentReservations'));
    }
}