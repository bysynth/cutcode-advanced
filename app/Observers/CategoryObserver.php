<?php

namespace App\Observers;

use Domain\Catalog\Models\Category;
use Illuminate\Support\Facades\Cache;

class CategoryObserver
{
    public function created(Category $category): void
    {
        $this->clearHomePageCache();
    }

    public function updated(Category $category): void
    {
        $this->clearHomePageCache();
    }

    public function deleted(Category $category): void
    {
        $this->clearHomePageCache();
    }

    private function clearHomePageCache(): void
    {
        Cache::forget('category_home_page');
    }
}
