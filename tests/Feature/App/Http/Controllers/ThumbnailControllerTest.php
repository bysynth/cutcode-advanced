<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Http\Controllers\ThumbnailController;
use Database\Factories\BrandFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ThumbnailControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $dir;
    private string $method;
    private string $size;
    private string $file;
    private string $path;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dir = 'brands';
        $this->method = 'resize';
        $this->size = '70x70';
        $this->file = basename(BrandFactory::new()->create()->thumbnail);
        $this->path = "$this->dir/$this->method/$this->size/$this->file";
    }

    private function request(): TestResponse
    {
        return $this->get(
            action(ThumbnailController::class, [
                'dir' => $this->dir,
                'method' => $this->method,
                'size' => $this->size,
                'file' => $this->file
            ])
        );
    }

    public function test_thumbnail_create_success(): void
    {
        $this->request()->assertOk();

        Storage::disk('images')->assertExists($this->path);
    }

    public function test_forbidden_when_size_is_not_allowed(): void
    {
        $this->size = '10x10';

        $this->request()->assertForbidden();

        Storage::disk('images')->assertMissing($this->path);
    }
}
