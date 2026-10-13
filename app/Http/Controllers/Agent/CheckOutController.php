<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Notifications\VehicleReturned;
use App\Notifications\InvoiceIssued;

class CheckOutController extends Controller
{
    public function index()
    {
        $contracts = Contract::with(['reservation.vehicle', 'client'])
            ->where('status', 'active')
            ->orWhere('status', 'overdue')
            ->orderBy('signed_at')
            ->paginate(15);

        return view('agent.checkout.index', compact('contracts'));
    }

    public function edit(Contract $contract)
    {
        if (!in_array($contract->status, ['active', 'overdue'])) {
            return redirect()
                ->route('agent.checkout.index')
                ->with('error', 'Ce contrat est déjà clôturé.');
        }

        $contract->load(['reservation.vehicle', 'client']);

        return view('agent.checkout.edit', compact('contract'));
    }

    public function update(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'return_mileage'         => 'required|integer|min:' . $contract->pickup_mileage,
            'return_fuel_level'      => 'required|in:empty,quarter,half,three_quarters,full',
            'return_condition'       => 'nullable|string|max:1000',
            'extra_charges'          => 'nullable|numeric|min:0',
            'extra_charges_notes'    => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($contract, $validated) {
            $extraCharges = $validated['extra_charges'] ?? 0;

            // Update contract
            $contract->update([
                'returned_at'         => now(),
                'return_mileage'      => $validated['return_mileage'],
                'return_fuel_level'   => $validated['return_fuel_level'],
                'return_condition'    => $validated['return_condition'] ?? null,
                'extra_charges'       => $extraCharges,
                'extra_charges_notes' => $validated['extra_charges_notes'] ?? null,
                'status'              => 'completed',
            ]);

            // Update vehicle
            $contract->reservation->vehicle->update([
                'status'  => 'available',
                'mileage' => $validated['return_mileage'],
            ]);

            // Update reservation
            $contract->reservation->update(['status' => 'completed']);

            // Generate invoice
            $reservation = $contract->reservation;
            $subtotal    = $reservation->subtotal + $extraCharges;
            $tax         = round($subtotal * 0.20, 2);

            Invoice::create([
                'invoice_number' => $this->generateInvoiceNumber(),
                'reservation_id' => $reservation->id,
                'client_id'      => $contract->client_id,
                'subtotal'       => $subtotal,
                'tax'            => $tax,
                'total'          => $subtotal + $tax,
                'status'         => 'unpaid',
                'issued_at'      => now(),
                'due_at'         => now()->addDays(15),
                'notes'          => $extraCharges > 0 ? 'Inclut frais supplémentaires : ' . ($validated['extra_charges_notes'] ?? '') : null,
            ]);
        });

        $contract->refresh();
        $contract->client->notify(new VehicleReturned($contract));

        return redirect()
            ->route('agent.contracts.show', $contract)
            ->with('success', 'Véhicule récupéré. Facture générée.');
    }

    protected function generateInvoiceNumber(): string
    {
        $prefix = 'INV-' . now()->format('Ymd') . '-';
        $count  = Invoice::whereDate('created_at', today())->count() + 1;

        return $prefix . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}