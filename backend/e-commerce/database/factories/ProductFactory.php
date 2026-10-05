<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->numberBetween(1000, 50000) / 100, // Entre $10 y $500
            'quantity' => $this->faker->numberBetween(5, 100),
            'sku' => 'SKU-' . strtoupper($this->faker->unique()->bothify('??##??##')),
            'image_url' => $this->faker->imageUrl(640, 480, 'product'),
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
