<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Address;
use App\Models\User;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'postal_code' => $this->faker->postcode,
            'address' => $this->faker->address,
            'building' => $this->faker->optional()->secondaryAddress,

        ];
    }
}
