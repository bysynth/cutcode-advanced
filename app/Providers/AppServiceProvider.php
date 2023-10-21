<?php

namespace App\Providers;

use App\Faker\FakerImageProvider;
use App\Http\Kernel;
use Carbon\CarbonInterval;
use Faker\Generator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
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

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::shouldBeStrict(!app()->isProduction());

        if (app()->isProduction()) {
//            DB::whenQueryingForLongerThan(
//                CarbonInterval::seconds(10),
//                function (Connection $connection, QueryExecuted $event) {
//                    logger()
//                        ->channel('telegram')
//                        ->debug('Querying Longer Than ' . $connection->totalQueryDuration() . ' ms');
//                }
//            );

            DB::listen(function (QueryExecuted $query) {
                // $query->sql
                // $query->bindings
                // $query->time

                if ($query->time > 1000) {
                    logger()
                        ->channel('stack')
                        ->debug('Query longer them 1000ms: ' . $query->sql, $query->bindings);
                }
            });

            app(Kernel::class)->whenRequestLifecycleIsLongerThan(
                CarbonInterval::seconds(4),
                function () {
                    logger()
                        ->channel('telegram')
                        ->debug('whenRequestLifecycleIsLongerThan:' . request()->url());
                }
            );
        }
    }
}
