<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\History;
use App\Models\Medicine;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HistoryMedicinesTest extends TestCase
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
    public function it_gets_history_medicines(): void
    {
        $history = History::factory()->create();
        $medicine = Medicine::factory()->create();

        $history->medicines()->attach($medicine);

        $response = $this->getJson(
            route('api.histories.medicines.index', $history)
        );

        $response->assertOk()->assertSee($medicine->name);
    }

    /**
     * @test
     */
    public function it_can_attach_medicines_to_history(): void
    {
        $history = History::factory()->create();
        $medicine = Medicine::factory()->create();

        $response = $this->postJson(
            route('api.histories.medicines.store', [$history, $medicine])
        );

        $response->assertNoContent();

        $this->assertTrue(
            $history
                ->medicines()
                ->where('medicines.id', $medicine->id)
                ->exists()
        );
    }

    /**
     * @test
     */
    public function it_can_detach_medicines_from_history(): void
    {
        $history = History::factory()->create();
        $medicine = Medicine::factory()->create();

        $response = $this->deleteJson(
            route('api.histories.medicines.store', [$history, $medicine])
        );

        $response->assertNoContent();

        $this->assertFalse(
            $history
                ->medicines()
                ->where('medicines.id', $medicine->id)
                ->exists()
        );
    }
}
