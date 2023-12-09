<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\CowType;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CowTypeControllerTest extends TestCase
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
    public function it_displays_index_view_with_cow_types(): void
    {
        $cowTypes = CowType::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('cow-types.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.cow_types.index')
            ->assertViewHas('cowTypes');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_cow_type(): void
    {
        $response = $this->get(route('cow-types.create'));

        $response->assertOk()->assertViewIs('app.cow_types.create');
    }

    /**
     * @test
     */
    public function it_stores_the_cow_type(): void
    {
        $data = CowType::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('cow-types.store'), $data);

        $this->assertDatabaseHas('cow_types', $data);

        $cowType = CowType::latest('id')->first();

        $response->assertRedirect(route('cow-types.edit', $cowType));
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_cow_type(): void
    {
        $cowType = CowType::factory()->create();

        $response = $this->get(route('cow-types.show', $cowType));

        $response
            ->assertOk()
            ->assertViewIs('app.cow_types.show')
            ->assertViewHas('cowType');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_cow_type(): void
    {
        $cowType = CowType::factory()->create();

        $response = $this->get(route('cow-types.edit', $cowType));

        $response
            ->assertOk()
            ->assertViewIs('app.cow_types.edit')
            ->assertViewHas('cowType');
    }

    /**
     * @test
     */
    public function it_updates_the_cow_type(): void
    {
        $cowType = CowType::factory()->create();

        $data = [
            'name' => $this->faker->name(),
        ];

        $response = $this->put(route('cow-types.update', $cowType), $data);

        $data['id'] = $cowType->id;

        $this->assertDatabaseHas('cow_types', $data);

        $response->assertRedirect(route('cow-types.edit', $cowType));
    }

    /**
     * @test
     */
    public function it_deletes_the_cow_type(): void
    {
        $cowType = CowType::factory()->create();

        $response = $this->delete(route('cow-types.destroy', $cowType));

        $response->assertRedirect(route('cow-types.index'));

        $this->assertModelMissing($cowType);
    }
}
