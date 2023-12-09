<?php

namespace Tests\Feature\Api;

use App\Models\Cow;
use App\Models\User;

use App\Models\Farm;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CowTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create(['email' => 'admin@admin.com']);

        Sanctum::actingAs($user, [], 'web');

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_gets_cows_list(): void
    {
        $cows = Cow::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.cows.index'));

        $response->assertOk()->assertSee($cows[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_cow(): void
    {
        $data = Cow::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.cows.store'), $data);

        $this->assertDatabaseHas('cows', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_cow(): void
    {
        $cow = Cow::factory()->create();

        $farm = Farm::factory()->create();

        $data = [
            'number' => $this->faker->randomNumber(),
            'name' => $this->faker->name(),
            'gender' => \Arr::random(['male', 'female']),
            'picture' => $this->faker->text(255),
            'parent_id' => $this->faker->randomNumber(),
            'mother_id' => $this->faker->randomNumber(),
            'owner' => $this->faker->text(255),
            'sold' => $this->faker->boolean(),
            'born' => $this->faker->date(),
            'farm_id' => $farm->id,
        ];

        $response = $this->putJson(route('api.cows.update', $cow), $data);

        $data['id'] = $cow->id;

        $this->assertDatabaseHas('cows', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_cow(): void
    {
        $cow = Cow::factory()->create();

        $response = $this->deleteJson(route('api.cows.destroy', $cow));

        $this->assertModelMissing($cow);

        $response->assertNoContent();
    }
}
