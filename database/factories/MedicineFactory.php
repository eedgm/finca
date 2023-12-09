<?php

namespace Database\Factories;

use App\Models\Medicine;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedicineFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Medicine::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'expiration_date' => $this->faker->date(),
            'code' => $this->faker->text(255),
            'cc' => $this->faker->randomNumber(1),
            'cost' => $this->faker->randomNumber(1),
            'manufacturer_id' => \App\Models\Manufacturer::factory(),
            'market_id' => \App\Models\Market::factory(),
        ];
    }
}
