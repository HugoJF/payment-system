<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderService;
use Exception;

class OrderController extends Controller
{
    /**
     * Initializes an order with a given payment processor if order is not yet initialized or redirects to .show route if it's.
     *
     * @param OrderService $service
     * @param Order        $order
     * @param              $type
     *
     * @return mixed
     */
    public function init(OrderService $service, Order $order, $type)
    {
        info("Trying to initialize order $order->id with type `$type`");
        $controller = $service->getControllerByType($type);

        // Check if order is already initialized
        if ($order->orderable_id) {
            return redirect()->route('orders.show', $order);
        }

        info("Forwarding call to $controller controller");

        // Forward controller call
        return app()->call("$controller@init", compact('order'));
    }

    /**
     * Redirects show route logic to correct controller type. If order is not initialized, show payment processor selector view.
     *
     * @param OrderService $service
     * @param Order        $order
     * @param null         $action
     *
     * @return \Illuminate\View\View
     * @throws Exception
     */
    public function show(OrderService $service, Order $order, $action = null)
    {
        if ($order->paid) {
            return view('orders.order-success', compact('order'));
        }

        if (!($type = $order->type())) {
            info("Order $order->id is not initialized, showing payment selector screen");

            return view('payment-selector', compact('order'));
        }

        $controller = $service->getControllerByClass($type);

        if (!$controller) {
            throw new Exception("Failed to find controller for order type $type from order $order->id");
        }

        return app()->call("$controller@show", compact('order', 'action'));
    }
}
