<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Reservation;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckInController extends Controller
{
    public function index()
    {
        // Reservations ready for pickup today or in the future
        $reservations = Reservation::with(['user', 'vehicle'])
            ->where('status', 'confirmed')
            ->whereDate('start_date', '<=', now()->addDays(3))
            ->orderBy('start_date')
            ->paginate(15);

        return view('agent.checkin.index', compact('reservations'));
    }

    public function create(Reservation $reservation)
    {
        if ($reservation->status !== 'confirmed') {
            return redirect()
                ->route('agent.checkin.index')
                ->with('error', 'Cette réservation n\'est pas prête pour le check-in.');
        }

        $reservation->load(['user', 'vehicle']);

        return view('agent.checkin.create', compact('reservation'));
    }

    public function store(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'pickup_mileage'      => 'required|integer|min:0',
            'pickup_fuel_level'   => 'required|in:empty,quarter,half,three_quarters,full',
            'pickup_condition'    => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($reservation, $validated) {
            // Create contract
            Contract::create([
                'contract_number'    => $this->generateContractNumber(),
                'reservation_id'     => $reservation->id,
                'client_id'          => $reservation->user_id,
                'agent_id'           => Auth::id(),
                'signed_at'          => now(),
                'pickup_mileage'     => $validated['pickup_mileage'],
                'pickup_fuel_level'  => $validated['pickup_fuel_level'],
                'pickup_condition'   => $validated['pickup_condition'] ?? null,
                'status'             => 'active',
            ]);

            // Update reservation status
            $reservation->update(['status' => 'active']);

            // Update vehicle status + mileage
            $reservation->vehicle->update([
                'status'  => 'rented',
                'mileage' => $validated['pickup_mileage'],
            ]);
        });

        return redirect()
            ->route('agent.contracts.show', $reservation->fresh()->contract)
            ->with('success', 'Contrat créé. Véhicule remis au client.');
    }

    protected function generateContractNumber(): string
    {
        $prefix = 'CTR-' . now()->format('Ymd') . '-';
        $count  = Contract::whereDate('created_at', today())->count() + 1;

        return $prefix . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}