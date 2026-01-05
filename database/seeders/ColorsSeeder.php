<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Color;

class ColorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            ['name' => 'Rojo', 'hex_code' => '#DC143C', 'description' => 'Color rojo'],
            ['name' => 'Negro', 'hex_code' => '#000000', 'description' => 'Color negro'],
            ['name' => 'Blanco', 'hex_code' => '#FFFFFF', 'description' => 'Color blanco'],
            ['name' => 'Marrón', 'hex_code' => '#8B4513', 'description' => 'Color marrón'],
            ['name' => 'Café', 'hex_code' => '#6F4E37', 'description' => 'Color café'],
            ['name' => 'Gris', 'hex_code' => '#808080', 'description' => 'Color gris'],
            ['name' => 'Amarillo', 'hex_code' => '#FFD700', 'description' => 'Color amarillo'],
            ['name' => 'Beige', 'hex_code' => '#F5F5DC', 'description' => 'Color beige'],
            ['name' => 'Crema', 'hex_code' => '#FFFDD0', 'description' => 'Color crema'],
            ['name' => 'Bayo', 'hex_code' => '#D2B48C', 'description' => 'Color bayo (amarillo pálido)'],
            ['name' => 'Palomino', 'hex_code' => '#DAA520', 'description' => 'Color palomino (dorado)'],
            ['name' => 'Ruano', 'hex_code' => '#C0C0C0', 'description' => 'Color ruano (mezcla de pelos blancos y oscuros)'],
            ['name' => 'Overo', 'hex_code' => null, 'description' => 'Patrón overo (manchas blancas irregulares)'],
            ['name' => 'Tobiano', 'hex_code' => null, 'description' => 'Patrón tobiano (manchas blancas grandes)'],
            ['name' => 'Pinto', 'hex_code' => null, 'description' => 'Patrón pinto (mezcla de colores)'],
        ];

        foreach ($colors as $colorData) {
            Color::firstOrCreate(['name' => $colorData['name']], $colorData);
        }
    }
}
