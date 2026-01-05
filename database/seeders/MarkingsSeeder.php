<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Marking;

class MarkingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $markings = [
            ['name' => 'Mancha en la frente', 'description' => 'Mancha blanca o de otro color en la frente'],
            ['name' => 'Mancha en el lomo', 'description' => 'Mancha en la parte superior del lomo'],
            ['name' => 'Mancha en el vientre', 'description' => 'Mancha en la parte inferior del vientre'],
            ['name' => 'Rayas', 'description' => 'Rayas en el cuerpo'],
            ['name' => 'Estrella', 'description' => 'Mancha en forma de estrella en la frente'],
            ['name' => 'Calcetines', 'description' => 'Manchas blancas en las patas'],
            ['name' => 'Listón', 'description' => 'Línea blanca desde la frente hasta el hocico'],
            ['name' => 'Parche en el ojo', 'description' => 'Mancha alrededor de uno o ambos ojos'],
            ['name' => 'Mancha en la cola', 'description' => 'Mancha en la cola'],
            ['name' => 'Lunares', 'description' => 'Pequeñas manchas circulares'],
            ['name' => 'Moteado', 'description' => 'Patrón moteado en todo el cuerpo'],
            ['name' => 'Atigrado', 'description' => 'Patrón de rayas como tigre'],
            ['name' => 'Bicolor', 'description' => 'Dos colores principales'],
            ['name' => 'Tricolor', 'description' => 'Tres colores principales'],
            ['name' => 'Sin marcas', 'description' => 'Sin marcas distintivas'],
        ];

        foreach ($markings as $markingData) {
            Marking::firstOrCreate(['name' => $markingData['name']], $markingData);
        }
    }
}
