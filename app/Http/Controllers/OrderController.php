<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderFormRequest;
use Domain\Order\Actions\NewOrderAction;
use Domain\Order\DTOs\OrderCustomerDTO;
use Domain\Order\DTOs\OrderDTO;
use Domain\Order\Models\DeliveryType;
use Domain\Order\Models\PaymentMethod;
use Domain\Order\Processes\AssignCustomer;
use Domain\Order\Processes\AssignProducts;
use Domain\Order\Processes\ChangeStateToPending;
use Domain\Order\Processes\CheckProductQuantities;
use Domain\Order\Processes\ClearCart;
use Domain\Order\Processes\DecreaseProductsQuantities;
use Domain\Order\Processes\OrderProcess;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Throwable;

class OrderController extends Controller
{
    public function index(): View
    {
        $items = cart()->items();

        if ($items->isEmpty()) {
            throw new DomainException('Корзина пуста');
        }

        return view('order.index', [
            'items' => $items,
            'payments' => PaymentMethod::get(),
            'deliveries' => DeliveryType::get(),
        ]);
    }

    /**
     * @throws Throwable
     */
    public function handle(OrderFormRequest $request, NewOrderAction $action): RedirectResponse
    {
        $order = $action(
            OrderDTO::make(...$request->only(['payment_method_id', 'delivery_type_id', 'password'])),
            OrderCustomerDTO::fromArray($request->input('customer')),
            $request->boolean('create_account')
        );

        (new OrderProcess($order))->processes([
            new CheckProductQuantities(),
            new AssignCustomer(OrderCustomerDTO::fromArray($request->input('customer'))),
            new AssignProducts(),
            new ChangeStateToPending(),
            new DecreaseProductsQuantities(),
            new ClearCart()
        ])->run();

        return redirect()->route('home');
    }
}
