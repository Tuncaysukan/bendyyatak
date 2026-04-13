<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Test Kullanıcı',
            'email'    => 'test@bendyyatak.com',
            'phone'    => '05551234567',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name'     => 'Demo Müşteri',
            'email'    => 'demo@bendyyatak.com',
            'phone'    => '05559876543',
            'password' => Hash::make('password123'),
        ]);
    }
}
