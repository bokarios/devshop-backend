<?php

namespace Database\Factories;

use App\Models\Category;
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
            'name'          => $this->faker->words(2, true),
            'description'   => $this->faker->sentences(3, true),
            'price'         => rand(100, 200),
            'image'         => 'default.png',
            'category_id'   => Category::all()->random()->id
        ];
    }
}
