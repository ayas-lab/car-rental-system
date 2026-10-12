<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->input('period', '30'); // days
        $from   = now()->subDays((int) $period);

        // --- KPIs ---
        $kpis = [
            'total_revenue'    => Invoice::where('status', 'paid')
                ->where('paid_at', '>=', $from)
                ->sum('total'),
            'pending_revenue'  => Invoice::whereIn('status', ['unpaid', 'overdue'])
                ->sum('total'),
            'total_reservations' => Reservation::where('created_at', '>=', $from)->count(),
            'active_rentals'   => Contract::where('status', 'active')->count(),
            'fleet_size'       => Vehicle::count(),
            'utilization_rate' => $this->utilizationRate(),
        ];

        // --- Revenue per day (last N days) ---
        $revenueChart = Invoice::where('status', 'paid')
            ->where('paid_at', '>=', $from)
            ->select(
                DB::raw('DATE(paid_at) as date'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // --- Most rented vehicles ---
        $topVehicles = Vehicle::withCount(['reservations' => function ($q) use ($from) {
                $q->where('created_at', '>=', $from);
            }])
            ->orderByDesc('reservations_count')
            ->take(5)
            ->get();

        // --- Reservation status distribution ---
        $statusBreakdown = Reservation::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        // --- Top clients by revenue ---
        $topClients = User::role('client')
            ->withSum(['invoices as total_paid' => function ($q) {
                $q->where('status', 'paid');
            }], 'total')
            ->orderByDesc('total_paid')
            ->take(5)
            ->get();

        return view('admin.reports.index', compact(
            'kpis',
            'revenueChart',
            'topVehicles',
            'statusBreakdown',
            'topClients',
            'period'
        ));
    }

    /**
     * Fleet utilization: % of vehicles currently rented vs total fleet.
     */
    protected function utilizationRate(): float
    {
        $total = Vehicle::count();
        if ($total === 0) return 0;

        $rented = Vehicle::where('status', 'rented')->count();

        return round(($rented / $total) * 100, 1);
    }

    /**
     * Export reports as CSV (simple version).
     */
    public function export(Request $request)
    {
        $period = (int) $request->input('period', 30);
        $from   = now()->subDays($period);

        $rows = Reservation::with(['user', 'vehicle'])
            ->where('created_at', '>=', $from)
            ->get()
            ->map(fn ($r) => [
                'reference'   => $r->reference,
                'client'      => $r->user->name,
                'vehicle'     => $r->vehicle->brand . ' ' . $r->vehicle->model,
                'start_date'  => $r->start_date->format('Y-m-d'),
                'end_date'    => $r->end_date->format('Y-m-d'),
                'total_price' => $r->total_price,
                'status'      => $r->status,
            ]);

        $filename = 'reservations-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($rows) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Référence', 'Client', 'Véhicule', 'Début', 'Fin', 'Total', 'Statut']);

            foreach ($rows as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}