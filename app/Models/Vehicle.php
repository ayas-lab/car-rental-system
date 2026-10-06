<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'registration_number', 'brand', 'model', 'year', 'color',
        'fuel_type', 'transmission', 'seats', 'doors', 'daily_rate',
        'mileage', 'status', 'image_path', 'description',
    ];

    protected $casts = [
        'daily_rate' => 'decimal:2',
        'year'       => 'integer',
        'mileage'    => 'integer',
    ];

    // Relationship: A vehicle has many reservations
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    // Helper: Check if vehicle is available for a given date range
    public function isAvailableFor($startDate, $endDate): bool
    {
        if ($this->status !== 'available') {
            return false;
        }

        return !$this->reservations()
            ->whereIn('status', ['pending', 'confirmed', 'active'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function ($q) use ($startDate, $endDate) {
                          $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                      });
            })
            ->exists();
    }
}