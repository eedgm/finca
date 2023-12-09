<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Medicine;
use App\Models\Manufacturer;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManufacturerMedicinesTest extends TestCase
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
    public function it_gets_manufacturer_medicines(): void
    {
        $manufacturer = Manufacturer::factory()->create();
        $medicines = Medicine::factory()
            ->count(2)
            ->create([
                'manufacturer_id' => $manufacturer->id,
            ]);

        $response = $this->getJson(
            route('api.manufacturers.medicines.index', $manufacturer)
        );

        $response->assertOk()->assertSee($medicines[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_manufacturer_medicines(): void
    {
        $manufacturer = Manufacturer::factory()->create();
        $data = Medicine::factory()
            ->make([
                'manufacturer_id' => $manufacturer->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.manufacturers.medicines.store', $manufacturer),
            $data
        );

        $this->assertDatabaseHas('medicines', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $medicine = Medicine::latest('id')->first();

        $this->assertEquals($manufacturer->id, $medicine->manufacturer_id);
    }
}
