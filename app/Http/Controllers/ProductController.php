<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __invoke(Product $product): View
    {
        $product->load(['optionValues.option']);

        $also = Product::where(function (Builder $query) use ($product) {
            $query->whereIn('id', session('also'))
                ->where('id', '!=', $product->id);
        })->get();

        $options = $product->optionValues->mapToGroups(function ($item) {
            return [$item->option->title => $item];
        });

        session()->put('also.' . $product->id, $product->id);

        return view('product.show', [
            'product' => $product,
            'options' => $options,
            'also' => $also,
        ]);
    }
}
