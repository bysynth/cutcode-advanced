<?php

namespace Domain\Order\Events;

use Domain\Order\Models\Order;
use Illuminate\Foundation\Events\Dispatchable;

class OrderCreated
{
    use Dispatchable;

    public function __construct(
        public Order $order
    ) {
    }
}
