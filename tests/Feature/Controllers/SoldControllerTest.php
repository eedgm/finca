<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Sold;

use App\Models\Cow;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SoldControllerTest extends TestCase
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
    public function it_displays_index_view_with_solds(): void
    {
        $solds = Sold::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('solds.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.solds.index')
            ->assertViewHas('solds');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_sold(): void
    {
        $response = $this->get(route('solds.create'));

        $response->assertOk()->assertViewIs('app.solds.create');
    }

    /**
     * @test
     */
    public function it_stores_the_sold(): void
    {
        $data = Sold::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('solds.store'), $data);

        $this->assertDatabaseHas('solds', $data);

        $sold = Sold::latest('id')->first();

        $response->assertRedirect(route('solds.edit', $sold));
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_sold(): void
    {
        $sold = Sold::factory()->create();

        $response = $this->get(route('solds.show', $sold));

        $response
            ->assertOk()
            ->assertViewIs('app.solds.show')
            ->assertViewHas('sold');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_sold(): void
    {
        $sold = Sold::factory()->create();

        $response = $this->get(route('solds.edit', $sold));

        $response
            ->assertOk()
            ->assertViewIs('app.solds.edit')
            ->assertViewHas('sold');
    }

    /**
     * @test
     */
    public function it_updates_the_sold(): void
    {
        $sold = Sold::factory()->create();

        $cow = Cow::factory()->create();

        $data = [
            'date' => $this->faker->date(),
            'pounds' => $this->faker->randomNumber(1),
            'kilograms' => $this->faker->randomNumber(1),
            'price' => $this->faker->randomFloat(2, 0, 9999),
            'number_sold' => $this->faker->text(255),
            'cow_id' => $cow->id,
        ];

        $response = $this->put(route('solds.update', $sold), $data);

        $data['id'] = $sold->id;

        $this->assertDatabaseHas('solds', $data);

        $response->assertRedirect(route('solds.edit', $sold));
    }

    /**
     * @test
     */
    public function it_deletes_the_sold(): void
    {
        $sold = Sold::factory()->create();

        $response = $this->delete(route('solds.destroy', $sold));

        $response->assertRedirect(route('solds.index'));

        $this->assertModelMissing($sold);
    }
}
