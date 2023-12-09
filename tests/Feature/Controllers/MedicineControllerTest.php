<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Medicine;

use App\Models\Market;
use App\Models\Manufacturer;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MedicineControllerTest extends TestCase
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
    public function it_displays_index_view_with_medicines(): void
    {
        $medicines = Medicine::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('medicines.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.medicines.index')
            ->assertViewHas('medicines');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_medicine(): void
    {
        $response = $this->get(route('medicines.create'));

        $response->assertOk()->assertViewIs('app.medicines.create');
    }

    /**
     * @test
     */
    public function it_stores_the_medicine(): void
    {
        $data = Medicine::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('medicines.store'), $data);

        $this->assertDatabaseHas('medicines', $data);

        $medicine = Medicine::latest('id')->first();

        $response->assertRedirect(route('medicines.edit', $medicine));
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_medicine(): void
    {
        $medicine = Medicine::factory()->create();

        $response = $this->get(route('medicines.show', $medicine));

        $response
            ->assertOk()
            ->assertViewIs('app.medicines.show')
            ->assertViewHas('medicine');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_medicine(): void
    {
        $medicine = Medicine::factory()->create();

        $response = $this->get(route('medicines.edit', $medicine));

        $response
            ->assertOk()
            ->assertViewIs('app.medicines.edit')
            ->assertViewHas('medicine');
    }

    /**
     * @test
     */
    public function it_updates_the_medicine(): void
    {
        $medicine = Medicine::factory()->create();

        $manufacturer = Manufacturer::factory()->create();
        $market = Market::factory()->create();

        $data = [
            'name' => $this->faker->name(),
            'expiration_date' => $this->faker->date(),
            'code' => $this->faker->text(255),
            'cc' => $this->faker->randomNumber(1),
            'cost' => $this->faker->randomNumber(1),
            'manufacturer_id' => $manufacturer->id,
            'market_id' => $market->id,
        ];

        $response = $this->put(route('medicines.update', $medicine), $data);

        $data['id'] = $medicine->id;

        $this->assertDatabaseHas('medicines', $data);

        $response->assertRedirect(route('medicines.edit', $medicine));
    }

    /**
     * @test
     */
    public function it_deletes_the_medicine(): void
    {
        $medicine = Medicine::factory()->create();

        $response = $this->delete(route('medicines.destroy', $medicine));

        $response->assertRedirect(route('medicines.index'));

        $this->assertModelMissing($medicine);
    }
}
