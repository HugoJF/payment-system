<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Order;
use App\OrderService;

class OrderController extends Controller
{
	/**
	 * @param OrderService $service
	 * @param Order        $order
	 * @param              $type
	 *
	 * @return mixed
	 */
	public function init(OrderService $service, Order $order, $type)
	{
		$controller = $service->getControllerByType($type);

		// Check for reinitialization
		if ($order->orderable_id)
			return redirect()->route('orders.show', $order);

		// Forward controller call
		return app()->call("$controller@init", compact('order'));
	}

	public function show(OrderService $service, Order $order, $action = null)
	{
		$type = $order->type();

		if (!$type)
			return view('payment-selector', compact('order'));

		$controller = $service->getControllerByClass($type);

		// Already pre recheck
		if (!$order->paid())
			$order->recheck();

		return app()->call("$controller@show", compact('order', 'action'));
	}
}
