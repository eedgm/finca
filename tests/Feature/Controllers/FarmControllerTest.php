<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Farm;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FarmControllerTest extends TestCase
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
    public function it_displays_index_view_with_farms(): void
    {
        $farms = Farm::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('farms.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.farms.index')
            ->assertViewHas('farms');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_farm(): void
    {
        $response = $this->get(route('farms.create'));

        $response->assertOk()->assertViewIs('app.farms.create');
    }

    /**
     * @test
     */
    public function it_stores_the_farm(): void
    {
        $data = Farm::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('farms.store'), $data);

        $this->assertDatabaseHas('farms', $data);

        $farm = Farm::latest('id')->first();

        $response->assertRedirect(route('farms.edit', $farm));
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_farm(): void
    {
        $farm = Farm::factory()->create();

        $response = $this->get(route('farms.show', $farm));

        $response
            ->assertOk()
            ->assertViewIs('app.farms.show')
            ->assertViewHas('farm');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_farm(): void
    {
        $farm = Farm::factory()->create();

        $response = $this->get(route('farms.edit', $farm));

        $response
            ->assertOk()
            ->assertViewIs('app.farms.edit')
            ->assertViewHas('farm');
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

        $response = $this->put(route('farms.update', $farm), $data);

        $data['id'] = $farm->id;

        $this->assertDatabaseHas('farms', $data);

        $response->assertRedirect(route('farms.edit', $farm));
    }

    /**
     * @test
     */
    public function it_deletes_the_farm(): void
    {
        $farm = Farm::factory()->create();

        $response = $this->delete(route('farms.destroy', $farm));

        $response->assertRedirect(route('farms.index'));

        $this->assertModelMissing($farm);
    }
}
