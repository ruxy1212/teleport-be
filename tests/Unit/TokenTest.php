<?php

namespace Tests\Unit;

use Tests\AuthTest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;
use Tymon\JWTAuth\Facades\JWTAuth;

class TokenTest extends AuthTest
{
    public function test_token_generation_and_expiry()
    {
        $user = User::factory()->create();

        // Authenticate as user1
        $this->actingAs($user);
        $token = JWTAuth::fromUser($user);


        $this->assertNotNull($token);

        $decoded = (array) json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $token)[1]))));

        // Assert that token expires at the correct time and correct user details is found in token
        $this->assertEquals($user->userId, $decoded['sub']);
        $this->assertTrue(isset($decoded['exp']));
    }
}