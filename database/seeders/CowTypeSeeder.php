<?php

namespace Database\Seeders;

use App\Models\CowType;
use Illuminate\Database\Seeder;

class CowTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CowType::create(['name' => 'Ternero']);
        CowType::create(['name' => 'Ternera']);
        CowType::create(['name' => 'Novillo']);
        CowType::create(['name' => 'Novilla']);
        CowType::create(['name' => 'Vaca']);
        CowType::create(['name' => 'Toro']);
    }
}
