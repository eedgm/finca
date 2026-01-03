<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Adding an admin user
        $user = \App\Models\User::factory()
            ->count(1)
            ->create([
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'password' => \Hash::make('admin'),
            ]);

        $this->call(PermissionsSeeder::class);

        // $this->call(CowSeeder::class);
        $this->call(CowTypeSeeder::class);
        // $this->call(FarmSeeder::class);
        // $this->call(HistorySeeder::class);
        // $this->call(ManufacturerSeeder::class);
        // $this->call(MarketSeeder::class);
        // $this->call(MedicineSeeder::class);
        // $this->call(SoldSeeder::class);
        // $this->call(UserSeeder::class);
    }
}
