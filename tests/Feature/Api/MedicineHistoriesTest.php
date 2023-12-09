<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\History;
use App\Models\Medicine;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MedicineHistoriesTest extends TestCase
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
    public function it_gets_medicine_histories(): void
    {
        $medicine = Medicine::factory()->create();
        $history = History::factory()->create();

        $medicine->histories()->attach($history);

        $response = $this->getJson(
            route('api.medicines.histories.index', $medicine)
        );

        $response->assertOk()->assertSee($history->date);
    }

    /**
     * @test
     */
    public function it_can_attach_histories_to_medicine(): void
    {
        $medicine = Medicine::factory()->create();
        $history = History::factory()->create();

        $response = $this->postJson(
            route('api.medicines.histories.store', [$medicine, $history])
        );

        $response->assertNoContent();

        $this->assertTrue(
            $medicine
                ->histories()
                ->where('histories.id', $history->id)
                ->exists()
        );
    }

    /**
     * @test
     */
    public function it_can_detach_histories_from_medicine(): void
    {
        $medicine = Medicine::factory()->create();
        $history = History::factory()->create();

        $response = $this->deleteJson(
            route('api.medicines.histories.store', [$medicine, $history])
        );

        $response->assertNoContent();

        $this->assertFalse(
            $medicine
                ->histories()
                ->where('histories.id', $history->id)
                ->exists()
        );
    }
}
