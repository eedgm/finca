<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Market;
use App\Models\Medicine;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MarketMedicinesTest extends TestCase
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
    public function it_gets_market_medicines(): void
    {
        $market = Market::factory()->create();
        $medicines = Medicine::factory()
            ->count(2)
            ->create([
                'market_id' => $market->id,
            ]);

        $response = $this->getJson(
            route('api.markets.medicines.index', $market)
        );

        $response->assertOk()->assertSee($medicines[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_market_medicines(): void
    {
        $market = Market::factory()->create();
        $data = Medicine::factory()
            ->make([
                'market_id' => $market->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.markets.medicines.store', $market),
            $data
        );

        $this->assertDatabaseHas('medicines', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $medicine = Medicine::latest('id')->first();

        $this->assertEquals($market->id, $medicine->market_id);
    }
}
