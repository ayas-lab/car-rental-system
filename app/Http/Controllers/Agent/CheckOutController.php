<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Reservation;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckOutController extends Controller
{
    /**
     * List confirmed reservations awaiting remise.
     */
    public function index()
    {
        $reservations = Reservation::with(['user', 'vehicle'])
            ->where('status', 'confirmed')
            ->whereDate('start_date', '<=', now()->addDays(3))
            ->orderBy('start_date')
            ->paginate(15);

        return view('agent.checkout.index', compact('reservations'));
    }

    /**
     * Show the remise form for a specific reservation.
     */
    public function create(Reservation $reservation)
    {
        if ($reservation->status !== 'confirmed') {
            return redirect()
                ->route('agent.checkout.index')
                ->with('error', 'Cette réservation n\'est pas prête pour la remise.');
        }

        $reservation->load(['user', 'vehicle']);

        return view('agent.checkout.create', compact('reservation'));
    }

    /**
     * Create the contract and hand the vehicle over.
     */
    public function store(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'pickup_mileage'    => 'required|integer|min:0',
            'pickup_fuel_level' => 'required|in:empty,quarter,half,three_quarters,full',
            'pickup_condition'  => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($reservation, $validated) {
            Contract::create([
                'contract_number'   => $this->generateContractNumber(),
                'reservation_id'    => $reservation->id,
                'client_id'         => $reservation->user_id,
                'agent_id'          => Auth::id(),
                'signed_at'         => now(),
                'pickup_mileage'    => $validated['pickup_mileage'],
                'pickup_fuel_level' => $validated['pickup_fuel_level'],
                'pickup_condition'  => $validated['pickup_condition'] ?? null,
                'status'            => 'active',
            ]);

            $reservation->update(['status' => 'active']);

            $reservation->vehicle->update([
                'status'  => 'rented',
                'mileage' => $validated['pickup_mileage'],
            ]);
        });

        $reservation->refresh();

        // Notify client
        if ($reservation->contract) {
            $reservation->user->notify(new \App\Notifications\VehiclePickedUp($reservation->contract));
        }

        return redirect()
            ->route('agent.contracts.show', $reservation->contract)
            ->with('success', 'Contrat créé. Véhicule remis au client.');
    }

    protected function generateContractNumber(): string
    {
        $prefix = 'CTR-' . now()->format('Ymd') . '-';
        $count  = Contract::whereDate('created_at', today())->count() + 1;

        return $prefix . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}