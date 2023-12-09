<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Manufacturer;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManufacturerControllerTest extends TestCase
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
    public function it_displays_index_view_with_manufacturers(): void
    {
        $manufacturers = Manufacturer::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('manufacturers.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.manufacturers.index')
            ->assertViewHas('manufacturers');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_manufacturer(): void
    {
        $response = $this->get(route('manufacturers.create'));

        $response->assertOk()->assertViewIs('app.manufacturers.create');
    }

    /**
     * @test
     */
    public function it_stores_the_manufacturer(): void
    {
        $data = Manufacturer::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('manufacturers.store'), $data);

        $this->assertDatabaseHas('manufacturers', $data);

        $manufacturer = Manufacturer::latest('id')->first();

        $response->assertRedirect(route('manufacturers.edit', $manufacturer));
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_manufacturer(): void
    {
        $manufacturer = Manufacturer::factory()->create();

        $response = $this->get(route('manufacturers.show', $manufacturer));

        $response
            ->assertOk()
            ->assertViewIs('app.manufacturers.show')
            ->assertViewHas('manufacturer');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_manufacturer(): void
    {
        $manufacturer = Manufacturer::factory()->create();

        $response = $this->get(route('manufacturers.edit', $manufacturer));

        $response
            ->assertOk()
            ->assertViewIs('app.manufacturers.edit')
            ->assertViewHas('manufacturer');
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

        $response = $this->put(
            route('manufacturers.update', $manufacturer),
            $data
        );

        $data['id'] = $manufacturer->id;

        $this->assertDatabaseHas('manufacturers', $data);

        $response->assertRedirect(route('manufacturers.edit', $manufacturer));
    }

    /**
     * @test
     */
    public function it_deletes_the_manufacturer(): void
    {
        $manufacturer = Manufacturer::factory()->create();

        $response = $this->delete(
            route('manufacturers.destroy', $manufacturer)
        );

        $response->assertRedirect(route('manufacturers.index'));

        $this->assertModelMissing($manufacturer);
    }
}
