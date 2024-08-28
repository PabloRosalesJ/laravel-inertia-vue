<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderDetail>
 */
class OrderDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => fake()->numberBetween(1, 20),
            'product_id' => fake()->numberBetween(1, 50),
            'quantity' => fake()->numberBetween(1, 3),
            'unit_price' => fake()->numberBetween(1, 1000) * 0.95
        ];
    }
}
