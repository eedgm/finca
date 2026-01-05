<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Breed;

class BreedsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $breeds = [
            [
                'name' => 'Brahman',
                'description' => 'Raza originaria del sur de Estados Unidos. Es la raza más popular en Panamá, representando aproximadamente el 85% del ganado. Se distingue por su alta resistencia a plagas y enfermedades, así como por su excelente adaptabilidad al trópico. Ideal para producción de carne.',
            ],
            [
                'name' => 'Guaymí',
                'description' => 'Raza criolla panameña presente en el país por más de 500 años. Posee un alto potencial para la producción de leche y carne, además de una mayor resistencia a enfermedades. Raza adaptada a las condiciones tropicales de Panamá.',
            ],
            [
                'name' => 'Guabalá',
                'description' => 'Raza criolla panameña con más de 500 años de presencia en el país. Caracterizada por su resistencia a enfermedades y adaptabilidad al clima tropical. Utilizada tanto para producción de leche como de carne.',
            ],
            [
                'name' => 'Senepol',
                'description' => 'Raza descendiente de la N\'Dama de Senegal, desarrollada en las Islas Vírgenes. Introducida en Panamá por ganaderos locales. Conocida por su resistencia al calor tropical y su eficiencia en la producción de carne y leche.',
            ],
            [
                'name' => 'Simmental',
                'description' => 'Raza suiza de doble propósito, utilizada tanto para la producción de leche como de carne. Se ha adaptado a diversos entornos y es apreciada por su fertilidad y rendimiento. Caracterizada por su tamaño grande y buena producción.',
            ],
            [
                'name' => 'Normando',
                'description' => 'Raza originaria de Normandía, Francia. Reconocida por la calidad de su leche, ideal para la producción de mantequilla y queso, así como por su carne marmoleada y de buen sabor. Raza de doble propósito.',
            ],
            [
                'name' => 'Holstein',
                'description' => 'Raza lechera por excelencia, originaria de Holanda. Reconocida mundialmente por su alta producción de leche. Se adapta bien a diferentes climas y es muy utilizada en sistemas de producción intensiva de leche.',
            ],
            [
                'name' => 'Gyr',
                'description' => 'Raza cebú originaria de la India. Caracterizada por su joroba y adaptabilidad al clima tropical. Excelente resistencia a enfermedades y parásitos. Utilizada principalmente para producción de leche en climas cálidos.',
            ],
            [
                'name' => 'Nelore',
                'description' => 'Raza cebú originaria de la India, muy popular en América Latina. Caracterizada por su resistencia al calor, enfermedades y parásitos. Excelente para producción de carne en condiciones tropicales.',
            ],
            [
                'name' => 'Angus',
                'description' => 'Raza originaria de Escocia, reconocida mundialmente por la calidad de su carne. Carne marmoleada de excelente sabor. Raza de tamaño mediano, sin cuernos, de color negro o rojo.',
            ],
            [
                'name' => 'Charolais',
                'description' => 'Raza originaria de Francia, reconocida por su tamaño grande y excelente producción de carne. Carne magra de alta calidad. Raza de color blanco o crema, muy utilizada en cruzamientos para mejorar el peso.',
            ],
            [
                'name' => 'Brangus',
                'description' => 'Raza sintética resultante del cruce entre Brahman y Angus. Combina la resistencia al trópico del Brahman con la calidad de carne del Angus. Muy popular en zonas tropicales y subtropicales.',
            ],
            [
                'name' => 'Santa Gertrudis',
                'description' => 'Raza desarrollada en Texas, resultado del cruce entre Brahman y Shorthorn. Caracterizada por su color rojo y adaptabilidad al clima cálido. Excelente para producción de carne en condiciones tropicales.',
            ],
            [
                'name' => 'Romosinuano',
                'description' => 'Raza criolla colombiana, también presente en Panamá. Caracterizada por ser mocha (sin cuernos) y de color rojo. Buena adaptación al trópico y resistencia a enfermedades. Utilizada para producción de carne y leche.',
            ],
            [
                'name' => 'Guzerat',
                'description' => 'Raza cebú originaria de la India, también conocida como Kankrej. Caracterizada por su tamaño grande y excelente producción de leche. Muy resistente al calor y enfermedades. Color gris plateado.',
            ],
            [
                'name' => 'Limousin',
                'description' => 'Raza originaria de Francia, reconocida por su excelente producción de carne magra. Carne de alta calidad con poco contenido de grasa. Raza de color rojo dorado, muy eficiente en conversión alimenticia.',
            ],
        ];

        foreach ($breeds as $breed) {
            Breed::firstOrCreate(
                ['name' => $breed['name']],
                ['description' => $breed['description']]
            );
        }

        $this->command->info('Razas bovinas de Panamá creadas exitosamente.');
    }
}
