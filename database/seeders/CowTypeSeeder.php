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
        CowType::create(['name' => 'Ternero', 'gender' => 'male']);
        CowType::create(['name' => 'Ternera', 'gender' => 'female']);
        CowType::create(['name' => 'Novillo', 'gender' => 'male']);
        CowType::create(['name' => 'Novilla', 'gender' => 'female']);
        CowType::create(['name' => 'Vaca', 'gender' => 'female']);
        CowType::create(['name' => 'Toro', 'gender' => 'male']);
    }
}
