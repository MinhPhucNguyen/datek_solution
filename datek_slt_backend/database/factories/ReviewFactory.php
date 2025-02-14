<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Product;
use Faker\Generator as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'review' => $this->faker->paragraph,
            'rating' => $this->faker->numberBetween(0, 5),
            'user_id' => User::all()->random()->id, 
            'product_id' => Product::query()->inRandomOrder()->first()->id,
        ];
    }
}
