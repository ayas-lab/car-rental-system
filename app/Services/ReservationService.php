<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReservationService
{
    public const TAX_RATE = 0.20; // 20% VAT

    /**
     * Calculate pricing for a reservation.
     */
    public function calculatePrice(Vehicle $vehicle, string $startDate, string $endDate): array
    {
        $start = Carbon::parse($startDate);
        $end   = Carbon::parse($endDate);

        $days     = $start->diffInDays($end) ?: 1; // at least 1 day
        $subtotal = $days * $vehicle->daily_rate;
        $tax      = round($subtotal * self::TAX_RATE, 2);
        $total    = $subtotal + $tax;

        return [
            'days'     => $days,
            'rate'     => $vehicle->daily_rate,
            'subtotal' => $subtotal,
            'tax'      => $tax,
            'total'    => $total,
        ];
    }

    /**
     * Check if a vehicle is available for the date range.
     */
    public function isAvailable(Vehicle $vehicle, string $startDate, string $endDate, ?int $ignoreReservationId = null): bool
    {
        if ($vehicle->status === 'maintenance' || $vehicle->status === 'out_of_service') {
            return false;
        }

        $query = $vehicle->reservations()
            ->whereIn('status', ['pending', 'confirmed', 'active'])
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate])
                  ->orWhere(function ($inner) use ($startDate, $endDate) {
                      $inner->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                  });
            });

        if ($ignoreReservationId) {
            $query->where('id', '!=', $ignoreReservationId);
        }

        return !$query->exists();
    }

    /**
     * Create a reservation within a DB transaction.
     */
    public function create(array $data, Vehicle $vehicle, int $userId): Reservation
    {
        return DB::transaction(function () use ($data, $vehicle, $userId) {
            $pricing = $this->calculatePrice($vehicle, $data['start_date'], $data['end_date']);

            return Reservation::create([
                'reference'    => $this->generateReference(),
                'user_id'      => $userId,
                'vehicle_id'   => $vehicle->id,
                'start_date'   => $data['start_date'],
                'end_date'     => $data['end_date'],
                'total_days'   => $pricing['days'],
                'daily_rate'   => $pricing['rate'],
                'subtotal'     => $pricing['subtotal'],
                'tax'          => $pricing['tax'],
                'total_price'  => $pricing['total'],
                'pickup_location'  => $data['pickup_location']  ?? null,
                'dropoff_location' => $data['dropoff_location'] ?? null,
                'status'       => 'pending',
                'notes'        => $data['notes'] ?? null,
            ]);
        });
    }

    protected function generateReference(): string
    {
        $prefix = 'RES-' . now()->format('Ymd') . '-';
        $count  = Reservation::whereDate('created_at', today())->count() + 1;

        return $prefix . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}