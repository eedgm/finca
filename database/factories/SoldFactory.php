<?php

namespace Database\Factories;

use App\Models\Sold;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class SoldFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Sold::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => $this->faker->date(),
            'pounds' => $this->faker->randomNumber(1),
            'kilograms' => $this->faker->randomNumber(1),
            'price' => $this->faker->randomFloat(2, 0, 9999),
            'number_sold' => $this->faker->text(255),
            'cow_id' => \App\Models\Cow::factory(),
        ];
    }
}
