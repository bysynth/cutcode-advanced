<?php

namespace Database\Factories;

use Domain\Catalog\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'title' => ucfirst(fake()->words(2, true)),
            'on_home_page' => fake()->boolean(),
            'sorting' => fake()->numberBetween(1, 999),
        ];
    }
}
