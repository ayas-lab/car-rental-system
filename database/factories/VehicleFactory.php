<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    public function definition(): array
    {
        $brands = ['Toyota', 'BMW', 'Mercedes', 'Audi', 'Renault', 'Peugeot', 'Ford', 'Honda'];
        $models = ['Corolla', 'X5', 'C-Class', 'A4', 'Clio', '208', 'Focus', 'Civic'];

        return [
            'registration_number' => strtoupper(fake()->bothify('??-###-??')),
            'brand'               => fake()->randomElement($brands),
            'model'               => fake()->randomElement($models),
            'year'                => fake()->numberBetween(2018, 2026),
            'color'               => fake()->safeColorName(),
            'fuel_type'           => fake()->randomElement(['petrol', 'diesel', 'electric', 'hybrid']),
            'transmission'        => fake()->randomElement(['manual', 'automatic']),
            'seats'               => fake()->randomElement([4, 5, 7]),
            'doors'               => fake()->randomElement([2, 4, 5]),
            'daily_rate'          => fake()->randomFloat(2, 30, 250),
            'mileage'             => fake()->numberBetween(0, 150000),
            'status'              => 'available',
            'description'         => fake()->sentence(),
        ];
    }
}