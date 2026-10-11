<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@carental.test',
            'password' => 'password@123',
            'phone'    => '+212 600-000-001',
            'is_active'=> true,
        ]);
        $admin->assignRole('admin');

        // Agent
        $agent = User::create([
            'name'     => 'Agent User',
            'email'    => 'agent@carental.test',
            'password' => 'agent@123',
            'phone'    => '+212 600-000-002',
            'is_active'=> true,
        ]);
        $agent->assignRole('agent');

        // Client
        $client = User::create([
            'name'     => 'Client User',
            'email'    => 'client@carental.test',
            'password' => 'client@123',
            'phone'    => '+212 600-000-003',
            'is_active'=> true,
        ]);
        $client->assignRole('client');
    }
}