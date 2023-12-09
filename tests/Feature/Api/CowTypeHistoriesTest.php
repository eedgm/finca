<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\CowType;
use App\Models\History;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CowTypeHistoriesTest extends TestCase
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
    public function it_gets_cow_type_histories(): void
    {
        $cowType = CowType::factory()->create();
        $histories = History::factory()
            ->count(2)
            ->create([
                'cow_type_id' => $cowType->id,
            ]);

        $response = $this->getJson(
            route('api.cow-types.histories.index', $cowType)
        );

        $response->assertOk()->assertSee($histories[0]->date);
    }

    /**
     * @test
     */
    public function it_stores_the_cow_type_histories(): void
    {
        $cowType = CowType::factory()->create();
        $data = History::factory()
            ->make([
                'cow_type_id' => $cowType->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.cow-types.histories.store', $cowType),
            $data
        );

        $this->assertDatabaseHas('histories', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $history = History::latest('id')->first();

        $this->assertEquals($cowType->id, $history->cow_type_id);
    }
}
