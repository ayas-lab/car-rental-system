<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    public function definition(): array
    {
        $start = fake()->dateTimeBetween('now', '+1 month');
        $end   = (clone $start)->modify('+' . fake()->numberBetween(1, 14) . ' days');
        $days  = $start->diff($end)->days;
        $rate  = fake()->randomFloat(2, 30, 250);
        $sub   = $days * $rate;
        $tax   = $sub * 0.20;

        return [
            'reference'    => 'RES-' . strtoupper(fake()->bothify('######-???')),
            'user_id'      => User::factory(),
            'vehicle_id'   => Vehicle::factory(),
            'start_date'   => $start,
            'end_date'     => $end,
            'total_days'   => $days,
            'daily_rate'   => $rate,
            'subtotal'     => $sub,
            'tax'          => $tax,
            'total_price'  => $sub + $tax,
            'status'       => 'pending',
        ];
    }
}