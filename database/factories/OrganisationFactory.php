<?php

namespace Database\Factories;

use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

class OrganisationFactory extends Factory
{
    protected $model = Organisation::class;

    public function definition()
    {
        return [
            'orgId' => Uuid::uuid4()->toString(),
            'name' => $this->faker->company,
            'description' => $this->faker->catchPhrase,
        ];
    }
}