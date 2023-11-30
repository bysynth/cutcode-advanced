<?php

namespace Database\Factories;

use Domain\Catalog\Models\Brand;
use Domain\Product\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'title' => ucfirst(fake()->words(2, true)),
            'thumbnail' => fake()->fixturesImage('products', 'products'),
            'price' => fake()->numberBetween(10000, 1000000),
            'brand_id' => Brand::inRandomOrder()->value('id'),
            'on_home_page' => fake()->boolean(),
            'sorting' => fake()->numberBetween(1, 999),
            'quantity' => fake()->numberBetween(0, 20),
            'text' => fake()->realText(),
        ];
    }
}
