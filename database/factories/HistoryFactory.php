<?php

namespace Database\Factories;

use App\Models\History;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class HistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = History::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => $this->faker->date(),
            'weight' => $this->faker->randomFloat(2, 0, 9999),
            'comments' => $this->faker->text(),
            'picture' => $this->faker->text(255),
            'cow_type_id' => \App\Models\CowType::factory(),
        ];
    }
}
