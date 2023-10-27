<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Brand::factory(20)->create();

        $categories = Category::factory(10)->create();

        Product::factory(30)
            ->create()
            ->each(fn(Product $product) => $product
                ->categories()
                ->sync($categories->random(rand(1, 3)))
            );
    }
}
