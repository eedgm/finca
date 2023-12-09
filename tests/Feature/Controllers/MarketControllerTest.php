<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Market;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MarketControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(
            User::factory()->create(['email' => 'admin@admin.com'])
        );

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_displays_index_view_with_markets(): void
    {
        $markets = Market::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('markets.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.markets.index')
            ->assertViewHas('markets');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_market(): void
    {
        $response = $this->get(route('markets.create'));

        $response->assertOk()->assertViewIs('app.markets.create');
    }

    /**
     * @test
     */
    public function it_stores_the_market(): void
    {
        $data = Market::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('markets.store'), $data);

        $this->assertDatabaseHas('markets', $data);

        $market = Market::latest('id')->first();

        $response->assertRedirect(route('markets.edit', $market));
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_market(): void
    {
        $market = Market::factory()->create();

        $response = $this->get(route('markets.show', $market));

        $response
            ->assertOk()
            ->assertViewIs('app.markets.show')
            ->assertViewHas('market');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_market(): void
    {
        $market = Market::factory()->create();

        $response = $this->get(route('markets.edit', $market));

        $response
            ->assertOk()
            ->assertViewIs('app.markets.edit')
            ->assertViewHas('market');
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

        $response = $this->put(route('markets.update', $market), $data);

        $data['id'] = $market->id;

        $this->assertDatabaseHas('markets', $data);

        $response->assertRedirect(route('markets.edit', $market));
    }

    /**
     * @test
     */
    public function it_deletes_the_market(): void
    {
        $market = Market::factory()->create();

        $response = $this->delete(route('markets.destroy', $market));

        $response->assertRedirect(route('markets.index'));

        $this->assertModelMissing($market);
    }
}
