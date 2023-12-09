<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\CowType;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CowTypeTest extends TestCase
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
    public function it_gets_cow_types_list(): void
    {
        $cowTypes = CowType::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.cow-types.index'));

        $response->assertOk()->assertSee($cowTypes[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_cow_type(): void
    {
        $data = CowType::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.cow-types.store'), $data);

        $this->assertDatabaseHas('cow_types', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_cow_type(): void
    {
        $cowType = CowType::factory()->create();

        $data = [
            'name' => $this->faker->name(),
        ];

        $response = $this->putJson(
            route('api.cow-types.update', $cowType),
            $data
        );

        $data['id'] = $cowType->id;

        $this->assertDatabaseHas('cow_types', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_cow_type(): void
    {
        $cowType = CowType::factory()->create();

        $response = $this->deleteJson(route('api.cow-types.destroy', $cowType));

        $this->assertModelMissing($cowType);

        $response->assertNoContent();
    }
}
