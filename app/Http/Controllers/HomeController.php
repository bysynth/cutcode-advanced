<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $categories = Category::homePage()->get();
        $products = Product::homePage()->get();
        $brands = Brand::homePage()->get();

        return view('index', compact(
            'categories',
            'products',
            'brands'
        ));
    }
}
