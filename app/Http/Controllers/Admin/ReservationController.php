<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Notifications\ReservationConfirmed;
use App\Notifications\ReservationCancelled;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::with(['user', 'vehicle']);

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('vehicle', function ($v) use ($search) {
                      $v->where('brand', 'like', "%{$search}%")
                        ->orWhere('registration_number', 'like', "%{$search}%");
                  });
            });
        }

        $reservations = $query->latest()->paginate(15)->withQueryString();

        return view('admin.reservations.index', compact('reservations'));
    }

    public function show(Reservation $reservation)
    {
        $reservation->load(['user', 'vehicle', 'contract', 'invoice']);

        return view('admin.reservations.show', compact('reservation'));
    }

    public function edit(Reservation $reservation)
    {
        return view('admin.reservations.edit', compact('reservation'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after:start_date',
            'pickup_location'  => 'nullable|string|max:100',
            'dropoff_location' => 'nullable|string|max:100',
            'notes'            => 'nullable|string|max:500',
        ]);

        $reservation->update($validated);

        return redirect()
            ->route('admin.reservations.show', $reservation)
            ->with('success', 'Réservation mise à jour.');
    }

    /**
     * Change the status of a reservation with business rules.
     */
    public function updateStatus(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,active,completed,cancelled',
        ]);

        $newStatus = $validated['status'];

        // Business rules for status transitions
        $allowed = [
            'pending'   => ['confirmed', 'cancelled'],
            'confirmed' => ['active', 'cancelled'],
            'active'    => ['completed'],
            'completed' => [],
            'cancelled' => [],
        ];

        if (!in_array($newStatus, $allowed[$reservation->status] ?? [])) {
            return back()->with('error',
                "Transition invalide : {$reservation->status} → {$newStatus}.");
        }

        $reservation->update(['status' => $newStatus]);

         // Trigger the right notification based on the new status
        if ($newStatus === 'confirmed') {
            $reservation->user->notify(new ReservationConfirmed($reservation));
        } elseif ($newStatus === 'cancelled') {
            $reservation->user->notify(new ReservationCancelled($reservation));
        }

        return back()->with('success', 'Statut mis à jour.');
    }

    public function destroy(Reservation $reservation)
    {
        // Only allow deletion of pending/cancelled reservations
        if (!in_array($reservation->status, ['pending', 'cancelled'])) {
            return back()->with('error', 'Seules les réservations en attente ou annulées peuvent être supprimées.');
        }

        $reservation->delete();

        return redirect()
            ->route('admin.reservations.index')
            ->with('success', 'Réservation supprimée.');
    }
}