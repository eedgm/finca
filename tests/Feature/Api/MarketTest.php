<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Market;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MarketTest extends TestCase
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
    public function it_gets_markets_list(): void
    {
        $markets = Market::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.markets.index'));

        $response->assertOk()->assertSee($markets[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_market(): void
    {
        $data = Market::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.markets.store'), $data);

        $this->assertDatabaseHas('markets', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_market(): void
    {
        $market = Market::factory()->create();

        $data = [
            'name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'direction' => $this->faker->text(),
        ];

        $response = $this->putJson(route('api.markets.update', $market), $data);

        $data['id'] = $market->id;

        $this->assertDatabaseHas('markets', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_market(): void
    {
        $market = Market::factory()->create();

        $response = $this->deleteJson(route('api.markets.destroy', $market));

        $this->assertModelMissing($market);

        $response->assertNoContent();
    }
}
