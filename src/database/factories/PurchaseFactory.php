<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Purchase;
use App\Models\User;
use App\Models\Item;
use App\Models\Address;

class PurchaseFactory extends Factory
{
    protected $model = Purchase::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'item_id' => Item::factory(),
            'address_id' => Address::factory(),
            'payment_method' => $this->faker->numberBetween(1, 2),
            'purchased_at' => now(),

        ];
    }
}
