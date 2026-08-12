<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVariantFactory extends Factory
{
    public function definition(): array
    {
        $weight = fake()->randomElement([
            250,
            500,
            1000,
        ]);

        return [
            'product_id' => Product::factory(),

            'name' => $weight . 'g',

            'sku' => 'TEST-' . fake()->unique()->numerify('#####'),

            'weight_grams' => $weight,

            'price' => fake()->randomFloat(
                2,
                500,
                5000
            ),

            'compare_at_price' => null,

            'stock_quantity' => fake()->numberBetween(
                0,
                100
            ),

            'low_stock_threshold' => 5,

            'is_active' => true,

            'sort_order' => 0,
        ];
    }
}
