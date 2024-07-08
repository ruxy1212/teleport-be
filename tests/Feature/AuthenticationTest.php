<?php


namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\AuthTest;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticationTest extends AuthTest
{
    use RefreshDatabase;

    public function test_register_user_successfully_with_default_organisation()
    {
        $response = $this->postJson(route('api.register'), [
            'firstName' => 'Ruxy',
            'lastName' => 'Oje',
            'email' => 'ruxy.oje@example.com',
            'password' => '1Password',
            'phone' => '09012345678'
        ]);

        // Ensure a user is registered successfully when no organisation details are provided.
        $response->assertStatus(201);

        // Check that the response contains the expected user details and access token.
        $response->assertJson([
            'status' => 'success',
            'message' => 'Registration successful',
            'data' => [
                'accessToken' => true, // just ensure it's present
                'user' => [
                    'userId' => true, // just ensure it's present
                    'firstName' => 'Ruxy',
                    'lastName' => 'Oje',
                    'email' => 'ruxy.oje@example.com',
                    'phone' => '09012345678'
                ]
            ]
        ]);

        //Verify user registration and the default organisation name is correctly generated
        $this->assertDatabaseHas('users', [
            'email' => 'ruxy.oje@example.com'
        ]);
        $user = User::where('email', 'ruxy.oje@example.com')->first();
        $organisation = $user->organisations()->first();
        $this->assertNotNull($organisation);
        $this->assertEquals("Ruxy's Organisation", $organisation->name);
    }

    public function test_log_in_user_successfully_or_fail_otherwise()
    {
        $user = User::factory()->create([
            'email' => 'ruxy.oje@example.com',
            'password' => Hash::make('1Password')
        ]);

        // Valid credentials
        $response = $this->postJson(route('api.login'), [
            'email' => 'ruxy.oje@example.com',
            'password' => '1Password'
        ]);

        // Ensure a user is logged in successfully for valid credential
        $response->assertStatus(200);

        // Check that the response contains the expected user details and access token.
        $response->assertJson([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'accessToken' => true,
                'user' => [
                    'userId' => $user->userId,
                    'firstName' => $user->firstName,
                    'lastName' => $user->lastName,
                    'email' => $user->email,
                    'phone' => $user->phone
                ]
            ]
        ]);

        // Invalid login attempt
        $response = $this->postJson(route('api.login'), [
            'email' => 'ruxy.oje@example.com',
            'password' => 'wrongpassword'
        ]);

        // Ensure when an invalid credential is provided it fails.
        $response->assertStatus(401);
        $response->assertJson([
            'status' => 'Bad request',
            'message' => 'Authentication failed',
            'statusCode' => 401
        ]);
    }

    public function test_fail_if_required_fields_are_missing()
    {
        // Missing firstName
        $response = $this->postJson(route('api.register'), [
            'lastName' => 'Oje',
            'email' => 'ruxy.oje@example.com',
            'password' => '1Password',
            'phone' => '09012345678'
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment(['field' => 'firstName']);

        // Missing lastName
        $response = $this->postJson(route('api.register'), [
            'firstName' => 'Ruxy',
            'email' => 'ruxy.oje@example.com',
            'password' => '1Password',
            'phone' => '09012345678'
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment(['field' => 'lastName']);

        // Missing email
        $response = $this->postJson(route('api.register'), [
            'firstName' => 'Ruxy',
            'lastName' => 'Oje',
            'password' => '1Password',
            'phone' => '09012345678'
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment(['field' => 'email']);

        // Missing password
        $response = $this->postJson(route('api.register'), [
            'firstName' => 'Ruxy',
            'lastName' => 'Oje',
            'email' => 'ruxy.oje@example.com',
            'phone' => '09012345678'
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment(['field' => 'password']);
    }

    public function test_fail_if_required_fields_are_missing_in_organisation()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $organisation1 = $user1->createOrganisation();
        $this->actingAs($user1);
        $token = JWTAuth::fromUser($user1);

        // Missing name when creating a new organisation
        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->postJson(route('api.organisations.store'));
        $response->assertStatus(422);
        $response->assertJsonFragment(['field' => 'name']);

        // Missing userId when adding a new user to organisation
        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->postJson(route('api.organisations.addUser', (string)$organisation1->orgId));
        $response->assertStatus(422);
        $response->assertJsonFragment(['field' => 'userId']);
    }

    public function test_fail_if_duplicate_email_or_userId()
    {
        User::factory()->create([
            'email' => 'ruxy.oje@example.com'
        ]);

        $response = $this->postJson(route('api.register'), [
            'firstName' => 'Ruxy',
            'lastName' => 'Oje',
            'email' => 'ruxy.oje@example.com',
            'password' => '1Password',
            'phone' => '09012345678'
        ]);

        // Ensure that email does not already exist
        $response->assertStatus(422);
        $response->assertJsonFragment(['field' => 'email']);
    }
}