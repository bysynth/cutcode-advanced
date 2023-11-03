<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Domain\Catalog\Models\Brand;
use Domain\Catalog\ViewModels\CategoryViewModel;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $categories = CategoryViewModel::make()
            ->homePage();

        $products = Product::homePage()->get();
        $brands = Brand::homePage()->get();

        return view('index', compact(
            'categories',
            'products',
            'brands'
        ));
    }
}
