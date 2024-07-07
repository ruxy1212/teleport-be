<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function it_generates_valid_token(): void
    {

        $user = User::create([
            'userId' => Str::uuid(),
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => Hash::make('password'),
            'phone' => '123456789',
        ]);

        $token = JWTAuth::fromUser($user);

        $this->assertNotNull($token);

    }
}
