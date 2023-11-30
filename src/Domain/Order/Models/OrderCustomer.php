<?php

namespace Domain\Order\Models;

use Illuminate\Database\Eloquent\Model;

class OrderCustomer extends Model
{
    protected $fillable = [
        'order_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
    ];
}
