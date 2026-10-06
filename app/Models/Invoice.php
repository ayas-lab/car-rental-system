<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number', 'reservation_id', 'client_id',
        'subtotal', 'tax', 'discount', 'total',
        'status', 'issued_at', 'due_at', 'paid_at',
        'pdf_path', 'notes',
    ];

    protected $casts = [
        'issued_at'  => 'date',
        'due_at'     => 'date',
        'paid_at'    => 'datetime',
        'subtotal'   => 'decimal:2',
        'tax'        => 'decimal:2',
        'discount'   => 'decimal:2',
        'total'      => 'decimal:2',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}