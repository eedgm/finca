<?php

namespace Tests\Feature\Api;

use App\Models\Cow;
use App\Models\User;
use App\Models\Farm;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FarmCowsTest extends TestCase
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
    public function it_gets_farm_cows(): void
    {
        $farm = Farm::factory()->create();
        $cows = Cow::factory()
            ->count(2)
            ->create([
                'farm_id' => $farm->id,
            ]);

        $response = $this->getJson(route('api.farms.cows.index', $farm));

        $response->assertOk()->assertSee($cows[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_farm_cows(): void
    {
        $farm = Farm::factory()->create();
        $data = Cow::factory()
            ->make([
                'farm_id' => $farm->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.farms.cows.store', $farm),
            $data
        );

        $this->assertDatabaseHas('cows', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $cow = Cow::latest('id')->first();

        $this->assertEquals($farm->id, $cow->farm_id);
    }
}
