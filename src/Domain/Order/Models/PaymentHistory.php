<?php

namespace Domain\Order\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    protected $fillable = [
        'payment_gateway',
        'meta',
        'payload',
    ];

    protected $casts = [
        'payload' => 'collection',
    ];
}
