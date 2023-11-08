<?php

namespace Database\Factories;

use App\Models\Product;
use Domain\Catalog\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => ucfirst(fake()->words(2, true)),
            'thumbnail' => fake()->fixturesImage('products', 'products'),
            'price' => fake()->numberBetween(1000, 10000),
            'brand_id' => Brand::inRandomOrder()->value('id'),
            'on_home_page' => fake()->boolean(),
            'sorting' => fake()->numberBetween(1, 999),
            'text' => fake()->realText(),
        ];
    }
}
