<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Domain\Catalog\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function __invoke(?Category $category): View
    {
        $categories = Category::has('products')
            ->select(['id', 'title', 'slug'])
            ->get();

        $products = Product::select(['id', 'title', 'slug', 'price', 'thumbnail', 'brand_id'])
            ->when(request('s'), function (Builder $query) {
                $query->whereFullText(['title', 'text'], request('s'));
            })
            ->when($category->exists, function (Builder $query) use ($category) {
                $query->whereRelation(
                    'categories',
                    'categories.id',
                    '=',
                    $category->id
                );
            })
            ->filtered()
            ->sorted()
            ->paginate(6);


        return view('catalog.index', [
            'categories' => $categories,
            'products' => $products,
            'category' => $category,
        ]);
    }
}
