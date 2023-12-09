<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Farm;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FarmUsersTest extends TestCase
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
    public function it_gets_farm_users(): void
    {
        $farm = Farm::factory()->create();
        $user = User::factory()->create();

        $farm->users()->attach($user);

        $response = $this->getJson(route('api.farms.users.index', $farm));

        $response->assertOk()->assertSee($user->name);
    }

    /**
     * @test
     */
    public function it_can_attach_users_to_farm(): void
    {
        $farm = Farm::factory()->create();
        $user = User::factory()->create();

        $response = $this->postJson(
            route('api.farms.users.store', [$farm, $user])
        );

        $response->assertNoContent();

        $this->assertTrue(
            $farm
                ->users()
                ->where('users.id', $user->id)
                ->exists()
        );
    }

    /**
     * @test
     */
    public function it_can_detach_users_from_farm(): void
    {
        $farm = Farm::factory()->create();
        $user = User::factory()->create();

        $response = $this->deleteJson(
            route('api.farms.users.store', [$farm, $user])
        );

        $response->assertNoContent();

        $this->assertFalse(
            $farm
                ->users()
                ->where('users.id', $user->id)
                ->exists()
        );
    }
}
