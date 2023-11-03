<?php

namespace Domain\Catalog\ViewModels;

use Domain\Catalog\Collections\CategoryCollection;
use Domain\Catalog\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Support\Traits\Makeable;

class CategoryViewModel
{
    use Makeable;

    public function homePage(): Collection|array|CategoryCollection
    {
        return Cache::rememberForever('category_home_page', function () {
            return Category::homePage()->get();
        });
    }
}
