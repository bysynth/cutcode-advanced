<?php

namespace App\Observers;

use Domain\Catalog\Models\Brand;
use Illuminate\Support\Facades\Cache;

class BrandObserver
{
    public function created(Brand $brand): void
    {
        $this->clearHomePageCache();
    }

    public function updated(Brand $brand): void
    {
        $this->clearHomePageCache();
    }

    private function clearHomePageCache(): void
    {
        Cache::forget('brand_home_page');
    }
}
