<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Reservation;
use App\Models\Vehicle;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'pending_checkouts'  => Reservation::where('status', 'confirmed')->count(),
            'active_contracts'  => Contract::where('status', 'active')->count(),
            'overdue_returns'   => Contract::where('status', 'overdue')->count(),
            'vehicles_available'=> Vehicle::where('status', 'available')->count(),
        ];

        $upcomingPickups = Reservation::with(['user', 'vehicle'])
            ->where('status', 'confirmed')
            ->whereDate('start_date', '>=', now())
            ->orderBy('start_date')
            ->take(5)
            ->get();

        return view('agent.dashboard', compact('stats', 'upcomingPickups'));
    }
}