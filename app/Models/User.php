<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'city',
        'country',
        'license_number',
        'license_expiry',
        'avatar',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'license_expiry'    => 'date',
            'is_active'         => 'boolean',
        ];
    }

    // ---------- Helper Methods ----------

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if the user is an agent.
     */
    public function isAgent(): bool
    {
        return $this->hasRole('agent');
    }

    /**
     * Check if the user is a client.
     */
    public function isClient(): bool
    {
        return $this->hasRole('client');
    }

    /**
     * Check if the user is staff (agent or admin).
     */
    public function isStaff(): bool
    {
        return $this->hasAnyRole(['agent', 'admin']);
    }

    // ---------- Relationships ----------

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function clientContracts()
    {
        return $this->hasMany(Contract::class, 'client_id');
    }

    public function agentContracts()
    {
        return $this->hasMany(Contract::class, 'agent_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'client_id');
    }
}