<?php

namespace Tests\Feature\Support\Casts;

use Database\Factories\ProductFactory;
use Domain\Product\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Support\ValueObjects\Price;
use Tests\TestCase;

class PriceCastTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = ProductFactory::new()->create([
            'price' => 100
        ]);
    }

    public function test_get_cast_success(): void
    {
        $price = $this->product->price;

        $this->assertInstanceOf(Price::class, $price);
        $this->assertEquals('100 ₽', str($price)->value());
    }

    public function test_set_cast_success(): void
    {
        $value = 200.71;
        $this->product->update([
            'price' => $value
        ]);

        $this->assertDatabaseHas('products', [
            'title' => $this->product->title,
            'price' => 20071
        ]);
    }
}
