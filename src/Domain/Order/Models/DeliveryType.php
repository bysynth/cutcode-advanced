<?php

namespace Domain\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Support\Casts\PriceCast;

class DeliveryType extends Model
{
    protected $fillable = [
        'title',
        'price',
        'with_address',
    ];

    protected $casts = [
        'price' => PriceCast::class
    ];
}
