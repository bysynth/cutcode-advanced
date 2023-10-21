<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Product;
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
            'thumbnail' => fake()->imagesFromFixtures('products', 'products'),
            'price' => fake()->numberBetween(1000, 100000),
            'brand_id' => Brand::inRandomOrder()->value('id'),
        ];
    }
}
