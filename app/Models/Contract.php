<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'contract_number', 'reservation_id', 'client_id', 'agent_id',
        'signed_at', 'pickup_mileage', 'pickup_fuel_level', 'pickup_condition',
        'returned_at', 'return_mileage', 'return_fuel_level', 'return_condition',
        'extra_charges', 'extra_charges_notes', 'status',
    ];

    protected $casts = [
        'signed_at'     => 'datetime',
        'returned_at'   => 'datetime',
        'extra_charges' => 'decimal:2',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
}