<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'userId' => Uuid::uuid4()->toString(),
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'),
            'phone' => $this->faker->phoneNumber,
        ];
    }
    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            $user->createOrganisation();
        });
    }
}