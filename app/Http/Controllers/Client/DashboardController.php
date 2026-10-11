<?php

namespace App\Http\Controllers\Client;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Reservation;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $stats = [
            'total_reservations' => $user->reservations()->count(),
            'active_reservations' => $user->reservations()
                ->whereIn('status', ['pending', 'confirmed', 'active'])
                ->count(),
            'unpaid_invoices' => $user->invoices()->where('status', 'unpaid')->count(),
        ];

        $upcomingReservation = $user->reservations()
            ->with('vehicle')
            ->whereIn('status', ['confirmed', 'active'])
            ->whereDate('end_date', '>=', now())
            ->orderBy('start_date')
            ->first();

        $recentInvoices = $user->invoices()
            ->latest()
            ->take(3)
            ->get();

        return view('client.dashboard', compact('stats', 'upcomingReservation', 'recentInvoices'));
    }
}