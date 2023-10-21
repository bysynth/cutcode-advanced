<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::creating(function (Model $model) {
            $model->slug = $model->slug ?? $model->makeSlug($model->{self::slugFrom()});
        });
    }

    public static function slugFrom(): string
    {
        return 'title';
    }

    protected function makeSlug(string $slugFromValue): string
    {
        $slug = str($slugFromValue)->slug()->value();

        if ($this->isSlugExists($slug)) {
            $slug = $this->getUniqueSlug($slug, 1);
        }

        return $slug;
    }

    protected function isSlugExists(string $slug): bool
    {
        return static::where('slug', $slug)->exists();
    }

    protected function getUniqueSlug(string $slug, int $iterator)
    {
        $newSlug = $slug . '-' . $iterator;

        if (!$this->isSlugExists($newSlug)) {
            return $newSlug;
        }

        return $this->getUniqueSlug($slug, ++$iterator);
    }
}
