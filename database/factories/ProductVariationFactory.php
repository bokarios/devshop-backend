<?php

namespace Database\Factories;

use App\Models\Color;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariation>
 */
class ProductVariationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'price'         => rand(100, 400),
            'image'         => 'default.png',
            'sizes'         => '["sm", "lg", "xl"]',
            'color_id'      => Color::all()->random()->id,
            'product_id'    => Product::all()->random()->id
        ];
    }
}
