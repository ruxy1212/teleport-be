<?php

namespace Tests\Unit;

use Tests\AuthTest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

class OrganisationTest extends AuthTest
{
    use RefreshDatabase;

    public function test_user_cannot_access_other_organisation_data()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $organisation2 = $user2->createOrganisation();

        $this->actingAs($user1);
        $token = JWTAuth::fromUser($user1);

        // Attempt to access user2's default organisation
        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->getJson(route('api.organisations.show', (string)$organisation2->orgId));

        // Assert that the response status is 403 Forbidden
        $response->assertStatus(403);
    }
}