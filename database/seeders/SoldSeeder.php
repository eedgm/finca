<?php

namespace Database\Seeders;

use App\Models\Sold;
use Illuminate\Database\Seeder;

class SoldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sold::factory()
            ->count(5)
            ->create();
    }
}
