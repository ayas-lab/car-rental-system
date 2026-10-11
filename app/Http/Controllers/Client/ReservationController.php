<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Vehicle;
use App\Services\ReservationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ReservationController extends Controller
{
    public function __construct(
        protected ReservationService $service
    ) {}

    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        $reservations = $user
            ->reservations()
            ->with('vehicle')
            ->latest()
            ->paginate(10);

        return view('client.reservations.index', compact('reservations'));
    }

    public function create(Vehicle $vehicle)
    {
        return view('client.reservations.create', compact('vehicle'));
    }

    public function store(Request $request, ReservationService $service)
    {
        $validated = $request->validate([
            'vehicle_id'       => 'required|exists:vehicles,id',
            'start_date'       => 'required|date|after_or_equal:today',
            'end_date'         => 'required|date|after:start_date',
            'pickup_location'  => 'nullable|string|max:100',
            'dropoff_location' => 'nullable|string|max:100',
            'notes'            => 'nullable|string|max:500',
        ]);

        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);

        if (!$service->isAvailable($vehicle, $validated['start_date'], $validated['end_date'])) {
            return back()
                ->withInput()
                ->withErrors(['start_date' => 'Ce véhicule n\'est pas disponible pour ces dates.']);
        }

        $reservation = $service->create($validated, $vehicle, Auth::id());

        return redirect()
            ->route('client.reservations.show', $reservation)
            ->with('success', 'Réservation créée avec succès. Référence : ' . $reservation->reference);
    }

    public function show(Reservation $reservation)
    {
        $this->authorizeReservation($reservation);
        $reservation->load(['vehicle', 'contract', 'invoice']);

        return view('client.reservations.show', compact('reservation'));
    }

    public function cancel(Reservation $reservation)
    {
        $this->authorizeReservation($reservation);

        if (!in_array($reservation->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Cette réservation ne peut plus être annulée.');
        }

        $reservation->update(['status' => 'cancelled']);

        return redirect()
            ->route('client.reservations.index')
            ->with('success', 'Réservation annulée.');
    }

    /**
     * Ensure the client can only see their own reservations.
     */
    protected function authorizeReservation(Reservation $reservation): void
    {
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à voir cette réservation.');
        }
    }
}