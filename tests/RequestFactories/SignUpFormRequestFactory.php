<?php

namespace Tests\RequestFactories;

use Worksome\RequestFactories\RequestFactory;

class SignUpFormRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'email' => fake()->email(),
            'name' => fake()->name(),
            'password' => fake()->password(8),
        ];
    }
}
