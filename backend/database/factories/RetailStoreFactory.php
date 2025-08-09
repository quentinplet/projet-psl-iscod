<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RetailStore>
 */
class RetailStoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        // Types de magasins français typiques
        $storeTypes = [
            'Carrefour',
            'Leclerc',
            'Intermarché',
            'Super U',
            'Casino',
            'Monoprix',
            'Franprix',
            'Géant',
            'Hyper U',
            'Simply Market'
        ];

        return [
            'name' => $this->faker->unique()->randomElement($storeTypes),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'description' => $this->faker->optional()->paragraph(),
        ];
    }

    /**
     * État pour magasins inactifs
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    /**
     * État pour magasins actifs
     */
    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * État pour grandes surfaces
     */
    public function hypermarket(): static
    {
        return $this->state(function (array $attributes) {
            $storeType = fake()->randomElement(['Carrefour', 'Leclerc', 'Géant']);
            $city = fake()->city();

            return [
                'name' => 'Hypermarché ' . $storeType . ' ' . $city,
                'code' => strtoupper($storeType[0] . $storeType[1]) . '_' . strtoupper(substr($city, 0, 3)) . '_' . fake()->unique()->numberBetween(100, 999),
                'description' => 'Grande surface alimentaire et non-alimentaire de plus de 2500m²',
            ];
        });
    }

    /**
     * État pour supermarchés
     */
    public function supermarket(): static
    {
        return $this->state(function (array $attributes) {
            $storeType = fake()->randomElement(['Super U', 'Intermarché', 'Casino']);
            $city = fake()->city();

            return [
                'name' => 'Supermarché ' . $storeType . ' ' . $city,
                'code' => strtoupper($storeType[0] . $storeType[1]) . '_' . strtoupper(substr($city, 0, 3)) . '_' . fake()->unique()->numberBetween(100, 999),
                'description' => 'Magasin en libre-service de 400 à 2500m²',
            ];
        });
    }
}
