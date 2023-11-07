<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Domain\Catalog\ViewModels\BrandViewModel;
use Domain\Catalog\ViewModels\CategoryViewModel;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $categories = CategoryViewModel::make()
            ->homePage();

        $brands = BrandViewModel::make()
            ->homePage();

        $products = Product::homePage()->get();

        return view('index', compact(
            'categories',
            'brands',
            'products',
        ));
    }
}
