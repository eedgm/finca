<?php

namespace Tests\Feature\Controllers;

use App\Models\Cow;
use App\Models\User;

use App\Models\Farm;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CowControllerTest extends TestCase
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
    public function it_displays_index_view_with_cows(): void
    {
        $cows = Cow::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('cows.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.cows.index')
            ->assertViewHas('cows');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_cow(): void
    {
        $response = $this->get(route('cows.create'));

        $response->assertOk()->assertViewIs('app.cows.create');
    }

    /**
     * @test
     */
    public function it_stores_the_cow(): void
    {
        $data = Cow::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('cows.store'), $data);

        $this->assertDatabaseHas('cows', $data);

        $cow = Cow::latest('id')->first();

        $response->assertRedirect(route('cows.edit', $cow));
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_cow(): void
    {
        $cow = Cow::factory()->create();

        $response = $this->get(route('cows.show', $cow));

        $response
            ->assertOk()
            ->assertViewIs('app.cows.show')
            ->assertViewHas('cow');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_cow(): void
    {
        $cow = Cow::factory()->create();

        $response = $this->get(route('cows.edit', $cow));

        $response
            ->assertOk()
            ->assertViewIs('app.cows.edit')
            ->assertViewHas('cow');
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

        $response = $this->put(route('cows.update', $cow), $data);

        $data['id'] = $cow->id;

        $this->assertDatabaseHas('cows', $data);

        $response->assertRedirect(route('cows.edit', $cow));
    }

    /**
     * @test
     */
    public function it_deletes_the_cow(): void
    {
        $cow = Cow::factory()->create();

        $response = $this->delete(route('cows.destroy', $cow));

        $response->assertRedirect(route('cows.index'));

        $this->assertModelMissing($cow);
    }
}
