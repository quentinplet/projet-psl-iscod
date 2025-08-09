<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
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
            'name' => fake()->text(),
            'image_path' => 'https://resource.logitech.com/content/dam/logitech/en/products/mice/mx-master-3s/migration-assets-for-delorean-2025/gallery/mx-master-3s-top-view-graphite.png',
            'description' => fake()->text(),
            'reference' => fake()->unique()->word(),
            'category_id' => fake()->numberBetween(1, 5),
            'supplier_id' => fake()->numberBetween(1, 5),
            'price' => fake()->randomFloat(2, 20, 5000),
            'quantity' => fake()->numberBetween(1, 100),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
