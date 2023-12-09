<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Farm;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserFarmsTest extends TestCase
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
    public function it_gets_user_farms(): void
    {
        $user = User::factory()->create();
        $farm = Farm::factory()->create();

        $user->farms()->attach($farm);

        $response = $this->getJson(route('api.users.farms.index', $user));

        $response->assertOk()->assertSee($farm->name);
    }

    /**
     * @test
     */
    public function it_can_attach_farms_to_user(): void
    {
        $user = User::factory()->create();
        $farm = Farm::factory()->create();

        $response = $this->postJson(
            route('api.users.farms.store', [$user, $farm])
        );

        $response->assertNoContent();

        $this->assertTrue(
            $user
                ->farms()
                ->where('farms.id', $farm->id)
                ->exists()
        );
    }

    /**
     * @test
     */
    public function it_can_detach_farms_from_user(): void
    {
        $user = User::factory()->create();
        $farm = Farm::factory()->create();

        $response = $this->deleteJson(
            route('api.users.farms.store', [$user, $farm])
        );

        $response->assertNoContent();

        $this->assertFalse(
            $user
                ->farms()
                ->where('farms.id', $farm->id)
                ->exists()
        );
    }
}
