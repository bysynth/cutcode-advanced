<?php

namespace Database\Factories;

use Domain\Catalog\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Brand>
 */
class BrandFactory extends Factory
{
    protected $model = Brand::class;

    public function definition(): array
    {
        return [
            'title' => fake()->company(),
            'thumbnail' => fake()->fixturesImage('brands', 'images/brands'),
            'on_home_page' => fake()->boolean(),
            'sorting' => fake()->numberBetween(1, 999),
        ];
    }
}
