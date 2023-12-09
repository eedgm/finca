<?php

namespace Database\Factories;

use App\Models\Cow;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class CowFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cow::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number' => $this->faker->randomNumber(),
            'name' => $this->faker->name(),
            'gender' => \Arr::random(['male', 'female']),
            'picture' => $this->faker->text(255),
            'parent_id' => $this->faker->randomNumber(),
            'mother_id' => $this->faker->randomNumber(),
            'owner' => $this->faker->text(255),
            'sold' => $this->faker->boolean(),
            'born' => $this->faker->date(),
            'farm_id' => \App\Models\Farm::factory(),
        ];
    }
}
