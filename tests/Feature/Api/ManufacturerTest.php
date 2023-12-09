<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Manufacturer;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManufacturerTest extends TestCase
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
    public function it_gets_manufacturers_list(): void
    {
        $manufacturers = Manufacturer::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.manufacturers.index'));

        $response->assertOk()->assertSee($manufacturers[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_manufacturer(): void
    {
        $data = Manufacturer::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.manufacturers.store'), $data);

        $this->assertDatabaseHas('manufacturers', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_manufacturer(): void
    {
        $manufacturer = Manufacturer::factory()->create();

        $data = [
            'name' => $this->faker->name(),
        ];

        $response = $this->putJson(
            route('api.manufacturers.update', $manufacturer),
            $data
        );

        $data['id'] = $manufacturer->id;

        $this->assertDatabaseHas('manufacturers', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_manufacturer(): void
    {
        $manufacturer = Manufacturer::factory()->create();

        $response = $this->deleteJson(
            route('api.manufacturers.destroy', $manufacturer)
        );

        $this->assertModelMissing($manufacturer);

        $response->assertNoContent();
    }
}
