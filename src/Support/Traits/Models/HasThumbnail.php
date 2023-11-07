<?php

namespace Support\Traits\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait HasThumbnail
{
    abstract protected function thumbnailDir(): string;

    public function makeThumbnail(string $size, string $method = 'resize'): string
    {
        return route('thumbnail', [
            'dir' => $this->thumbnailDir(),
            'method' => $method,
            'size' => $size,
            'file' => File::basename($this->{$this->thumbnailColumn()})
        ]);
    }

    protected function thumbnailColumn(): string
    {
        return 'thumbnail';
    }

    protected static function bootHasThumbnail(): void
    {
        static::deleting(function (Model $item) {
            $item->deleteThumbnail();
        });
    }

    protected function deleteThumbnail(): void
    {
        if (!$this->{$this->thumbnailColumn()}) {
            return;
        }

        $thumbnail = $this->{$this->thumbnailColumn()};
        $thumbnailName = File::basename($thumbnail);
        $storage = Storage::disk('images');

        $files = collect($storage->allFiles($this->thumbnailDir()))
            ->filter(fn($item) => str($item)->contains($thumbnailName))
            ->toArray();

        $storage->delete($files);
    }
}
