<?php

namespace Domain\Order\Actions;

use App\Http\Requests\OrderFormRequest;
use Domain\Auth\Contracts\RegisterNewUserContract;
use Domain\Auth\DTOs\NewUserDTO;
use Domain\Order\Models\Order;

class NewOrderAction
{
    public function __invoke(OrderFormRequest $request): Order
    {
        $registerAction = app(RegisterNewUserContract::class);

        $customer = $request->input('customer');

        if ($request->boolean('create_account')) {
            $registerAction(NewUserDTO::make(
                $customer['first_name'] . ' ' . $customer['last_name'],
                $customer['email'],
                $request->input('password')
            ));
        }

        return Order::create([
            'payment_method_id' => $request->input('payment_method_id'),
            'delivery_type_id' => $request->input('delivery_type_id'),
        ]);
    }
}
