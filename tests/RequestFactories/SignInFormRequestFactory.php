<?php

namespace Tests\RequestFactories;

use Worksome\RequestFactories\RequestFactory;

class SignInFormRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'email' => fake()->email(),
            'password' => fake()->password(8),
        ];
    }
}
