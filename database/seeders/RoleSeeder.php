<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //creating the roles
        Role::create(['name'=> 'client']);
        Role::create(['name'=> 'agent']);
        Role::create(['name'=> 'admin']);
    }
}
