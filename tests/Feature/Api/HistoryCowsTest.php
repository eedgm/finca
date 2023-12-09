<?php

namespace Tests\Feature\Api;

use App\Models\Cow;
use App\Models\User;
use App\Models\History;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HistoryCowsTest extends TestCase
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
    public function it_gets_history_cows(): void
    {
        $history = History::factory()->create();
        $cow = Cow::factory()->create();

        $history->cows()->attach($cow);

        $response = $this->getJson(route('api.histories.cows.index', $history));

        $response->assertOk()->assertSee($cow->name);
    }

    /**
     * @test
     */
    public function it_can_attach_cows_to_history(): void
    {
        $history = History::factory()->create();
        $cow = Cow::factory()->create();

        $response = $this->postJson(
            route('api.histories.cows.store', [$history, $cow])
        );

        $response->assertNoContent();

        $this->assertTrue(
            $history
                ->cows()
                ->where('cows.id', $cow->id)
                ->exists()
        );
    }

    /**
     * @test
     */
    public function it_can_detach_cows_from_history(): void
    {
        $history = History::factory()->create();
        $cow = Cow::factory()->create();

        $response = $this->deleteJson(
            route('api.histories.cows.store', [$history, $cow])
        );

        $response->assertNoContent();

        $this->assertFalse(
            $history
                ->cows()
                ->where('cows.id', $cow->id)
                ->exists()
        );
    }
}
