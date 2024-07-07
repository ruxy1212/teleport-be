<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test token generation and expiration.
     */
    // public function it_generates_valid_token()
    // {

    //     $user = User::create([
    //         'userId' => Str::uuid(),
    //         'firstName' => 'John',
    //         'lastName' => 'Doe',
    //         'email' => 'john.doe@example.com',
    //         'password' => Hash::make('password'),
    //         'phone' => '123456789',
    //     ]);

    //     $token = JWTAuth::fromUser($user);

    //     $this->assertNotNull($token);

    // }
    // public function test_token_generation_and_expiration()
    // {
    //     // Create a user
    //     $user = User::factory()->create();

    //     // Generate JWT token
    //     $token = JWTAuth::fromUser($user);

    //     // Decode the token to get payload
    //     $payload = JWTAuth::setToken($token)->getPayload();

    //     // Assert the payload contains the correct user ID
    //     $this->assertEquals($user->userId, $payload['sub']);

    //     // Assert the token expires at the correct time (60 minutes)
    //     $expiresAt = $payload['exp'];
    //     $expectedExpiration = now()->addMinutes(config('jwt.ttl'))->timestamp;
    //     $this->assertEquals($expectedExpiration, $expiresAt);
    // }
}
