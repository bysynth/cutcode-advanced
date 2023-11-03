<?php

namespace Feature\App\Http\Controllers;

use App\Http\Controllers\HomeController;
use Database\Factories\BrandFactory;
use Database\Factories\CategoryFactory;
use Database\Factories\ProductFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_success_response()
    {
        BrandFactory::new()->count(5)->create([
            'on_home_page' => true,
            'sorting' => 999
        ]);

        $brand = BrandFactory::new()->createOne([
            'on_home_page' => true,
            'sorting' => 1
        ]);

        CategoryFactory::new()->count(5)->create([
            'on_home_page' => true,
            'sorting' => 999
        ]);

        $category = CategoryFactory::new()->createOne([
            'on_home_page' => true,
            'sorting' => 1
        ]);

        ProductFactory::new()->count(5)->create([
            'on_home_page' => true,
            'sorting' => 999
        ]);

        $product = ProductFactory::new()->createOne([
            'on_home_page' => true,
            'sorting' => 1
        ]);

        $this->get(action(HomeController::class))
            ->assertOk()
            ->assertViewHas('brands.0', $brand)
            ->assertViewHas('categories.0', $category)
            ->assertViewHas('products.0', $product);
    }
}
