<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Order;
use App\OrderService;
use Illuminate\Support\Facades\Log;

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
		Log::info("Trying to initialize order $order->id with type `$type`");
		$controller = $service->getControllerByType($type);

		// Check for reinitialization
		if ($order->orderable_id)
			return redirect()->route('orders.show', $order);

		Log::info("Forwarding call to $controller controller");
		// Forward controller call
		return app()->call("$controller@init", compact('order'));
	}

	public function show(OrderService $service, Order $order, $action = null)
	{
		$type = $order->type();

		if (!$type) {
			Log::info("Order $order->id is not initialized, showing payment selector screen");

			return view('payment-selector', compact('order'));
		}
		$controller = $service->getControllerByClass($type);

		// Already pre recheck
		if (!$order->paid())
			$order->recheck();

		return app()->call("$controller@show", compact('order', 'action'));
	}
}
