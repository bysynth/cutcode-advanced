<?php

namespace App\Faker;

use Faker\Provider\Base;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FakerImageProvider extends Base
{
    public function imagesFromFixtures(string $fromDirectory, string $toPublicDirectory): string
    {
        $fixturesPath = 'tests/Fixtures/images';
        $toPublicDirectory = 'images' . DIRECTORY_SEPARATOR . $toPublicDirectory;

        if (Storage::directoryMissing($toPublicDirectory)) {
            Storage::makeDirectory($toPublicDirectory);
        }

        $files = Arr::map(
            File::files(base_path($fixturesPath) . DIRECTORY_SEPARATOR . $fromDirectory),
            fn($file) => $file->getPathname()
        );

        $randomFile = Arr::random($files);

        return Storage::putFile($toPublicDirectory, $randomFile);
    }
}
