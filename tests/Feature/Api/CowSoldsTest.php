<?php

namespace Tests\Feature\Api;

use App\Models\Cow;
use App\Models\User;
use App\Models\Sold;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CowSoldsTest extends TestCase
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
    public function it_gets_cow_solds(): void
    {
        $cow = Cow::factory()->create();
        $solds = Sold::factory()
            ->count(2)
            ->create([
                'cow_id' => $cow->id,
            ]);

        $response = $this->getJson(route('api.cows.solds.index', $cow));

        $response->assertOk()->assertSee($solds[0]->date);
    }

    /**
     * @test
     */
    public function it_stores_the_cow_solds(): void
    {
        $cow = Cow::factory()->create();
        $data = Sold::factory()
            ->make([
                'cow_id' => $cow->id,
            ])
            ->toArray();

        $response = $this->postJson(route('api.cows.solds.store', $cow), $data);

        $this->assertDatabaseHas('solds', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $sold = Sold::latest('id')->first();

        $this->assertEquals($cow->id, $sold->cow_id);
    }
}
