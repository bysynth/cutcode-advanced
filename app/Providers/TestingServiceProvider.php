<?php

namespace App\Providers;

use App\Faker\FakerImageProvider;
use Faker\Generator;
use Illuminate\Support\ServiceProvider;

class TestingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
//        $this->app->singleton(Generator::class, function () {
//            $faker = Factory::create();
//            $faker->addProvider(new FakerImageProvider($faker));
//            return $faker;
//        });

        $this->app->afterResolving(function (mixed $instance) {
            if ($instance instanceof Generator) {
                $instance->addProvider(new FakerImageProvider($instance));
            }
        });
    }

    public function boot(): void
    {
    }
}
