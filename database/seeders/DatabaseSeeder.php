<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            SettingsSeeder::class,
            CategorySeeder::class,
            InstallmentPlanSeeder::class,
            UserSeeder::class,
            ProductSeeder::class,
            EvlineSeeder::class,
        ]);
    }
}
