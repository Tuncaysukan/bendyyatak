<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'name'     => 'BendyyYatak Admin',
            'email'    => 'admin@bendyyatak.com',
            'password' => Hash::make('bendyy2024!'),
            'role'     => 'superadmin',
            'is_active'=> true,
        ]);
    }
}
