<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Organisation;

class OrganisationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test users can only see organisations they have access to.
     */
    public function test_user_cannot_see_organisations_they_dont_have_access_to()
    {
        // Create two users
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Create organisations
        $organisation1 = Organisation::factory()->create();
        $organisation2 = Organisation::factory()->create();

        // Assign user1 to organisation1
        $user1->organisations()->attach($organisation1->orgId);

        // Assign user2 to organisation2
        $user2->organisations()->attach($organisation2->orgId);

        // Acting as user1, attempt to fetch organisations
        $response = $this->actingAs($user1, 'api')->getJson('/api/organisations');

        // Assert user1 can see organisation1 but not organisation2
        $response->assertStatus(200);
        $response->assertJsonFragment(['orgId' => $organisation1->orgId]);
        $response->assertJsonMissing(['orgId' => $organisation2->orgId]);
    }
}
