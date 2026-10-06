<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reference', 'user_id', 'vehicle_id', 'start_date', 'end_date',
        'total_days', 'daily_rate', 'subtotal', 'tax', 'total_price',
        'pickup_location', 'dropoff_location', 'status', 'notes',
    ];

    protected $casts = [
        'start_date'  => 'date',
        'end_date'    => 'date',
        'daily_rate'  => 'decimal:2',
        'subtotal'    => 'decimal:2',
        'tax'         => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function contract()
    {
        return $this->hasOne(Contract::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}