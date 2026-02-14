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
            'name' => 'Agua ' . $this->faker->word,
            'sku' => $this->faker->unique()->ean8,
            'unit_type' => $this->faker->randomElement(['paca', 'unidad', 'botellon']),
            'sale_price' => $this->faker->randomFloat(2, 1000, 50000),
        ];
    }
}
