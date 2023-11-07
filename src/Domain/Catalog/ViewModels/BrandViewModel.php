<?php

namespace Domain\Catalog\ViewModels;

use Domain\Catalog\Collections\BrandCollection;
use Domain\Catalog\Models\Brand;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Support\Traits\Makeable;

class BrandViewModel
{
    use Makeable;

    public function homePage(): Collection|array|BrandCollection
    {
        return Cache::rememberForever('brand_home_page', function () {
            return Brand::homePage()->get();
        });
    }
}
