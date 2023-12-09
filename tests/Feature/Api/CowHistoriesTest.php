<?php

namespace Tests\Feature\Api;

use App\Models\Cow;
use App\Models\User;
use App\Models\History;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CowHistoriesTest extends TestCase
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
    public function it_gets_cow_histories(): void
    {
        $cow = Cow::factory()->create();
        $history = History::factory()->create();

        $cow->histories()->attach($history);

        $response = $this->getJson(route('api.cows.histories.index', $cow));

        $response->assertOk()->assertSee($history->date);
    }

    /**
     * @test
     */
    public function it_can_attach_histories_to_cow(): void
    {
        $cow = Cow::factory()->create();
        $history = History::factory()->create();

        $response = $this->postJson(
            route('api.cows.histories.store', [$cow, $history])
        );

        $response->assertNoContent();

        $this->assertTrue(
            $cow
                ->histories()
                ->where('histories.id', $history->id)
                ->exists()
        );
    }

    /**
     * @test
     */
    public function it_can_detach_histories_from_cow(): void
    {
        $cow = Cow::factory()->create();
        $history = History::factory()->create();

        $response = $this->deleteJson(
            route('api.cows.histories.store', [$cow, $history])
        );

        $response->assertNoContent();

        $this->assertFalse(
            $cow
                ->histories()
                ->where('histories.id', $history->id)
                ->exists()
        );
    }
}
