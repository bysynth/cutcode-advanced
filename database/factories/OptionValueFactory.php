<?php

namespace Database\Factories;

use Domain\Product\Models\Option;
use Domain\Product\Models\OptionValue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Domain\Product\Models\OptionValue>
 */
class OptionValueFactory extends Factory
{
    protected $model = OptionValue::class;

    public function definition(): array
    {
        return [
            'option_id' => Option::inRandomOrder()->value('id'),
            'title' => ucfirst(fake()->word()),
        ];
    }
}
