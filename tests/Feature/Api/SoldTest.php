<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Sold;

use App\Models\Cow;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SoldTest extends TestCase
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
    public function it_gets_solds_list(): void
    {
        $solds = Sold::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.solds.index'));

        $response->assertOk()->assertSee($solds[0]->date);
    }

    /**
     * @test
     */
    public function it_stores_the_sold(): void
    {
        $data = Sold::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.solds.store'), $data);

        $this->assertDatabaseHas('solds', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
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

        $response = $this->putJson(route('api.solds.update', $sold), $data);

        $data['id'] = $sold->id;

        $this->assertDatabaseHas('solds', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_sold(): void
    {
        $sold = Sold::factory()->create();

        $response = $this->deleteJson(route('api.solds.destroy', $sold));

        $this->assertModelMissing($sold);

        $response->assertNoContent();
    }
}
