<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Item;
use App\Models\User;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        return [
            'img_url' => 'sample.jpg',
            'user_id' => User::factory(),
            'title' => $this->faker->word(),
            'brand' => $this->faker->company(),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->numberBetween(500, 10000),
            'condition' => $this->faker->numberBetween(1, 5),
            'status' => 0,
        ];
    }
}
