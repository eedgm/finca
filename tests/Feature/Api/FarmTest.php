<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Farm;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FarmTest extends TestCase
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
    public function it_gets_farms_list(): void
    {
        $farms = Farm::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.farms.index'));

        $response->assertOk()->assertSee($farms[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_farm(): void
    {
        $data = Farm::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.farms.store'), $data);

        $this->assertDatabaseHas('farms', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_farm(): void
    {
        $farm = Farm::factory()->create();

        $data = [
            'name' => $this->faker->name(),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'description' => $this->faker->sentence(15),
            'cattle_brand' => $this->faker->text(255),
        ];

        $response = $this->putJson(route('api.farms.update', $farm), $data);

        $data['id'] = $farm->id;

        $this->assertDatabaseHas('farms', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_farm(): void
    {
        $farm = Farm::factory()->create();

        $response = $this->deleteJson(route('api.farms.destroy', $farm));

        $this->assertModelMissing($farm);

        $response->assertNoContent();
    }
}
