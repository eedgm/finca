<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Medicine;

use App\Models\Market;
use App\Models\Manufacturer;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MedicineTest extends TestCase
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
    public function it_gets_medicines_list(): void
    {
        $medicines = Medicine::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.medicines.index'));

        $response->assertOk()->assertSee($medicines[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_medicine(): void
    {
        $data = Medicine::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.medicines.store'), $data);

        $this->assertDatabaseHas('medicines', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
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

        $response = $this->putJson(
            route('api.medicines.update', $medicine),
            $data
        );

        $data['id'] = $medicine->id;

        $this->assertDatabaseHas('medicines', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_medicine(): void
    {
        $medicine = Medicine::factory()->create();

        $response = $this->deleteJson(
            route('api.medicines.destroy', $medicine)
        );

        $this->assertModelMissing($medicine);

        $response->assertNoContent();
    }
}
