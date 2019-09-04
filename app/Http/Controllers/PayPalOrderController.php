<?php

namespace App\Http\Controllers;

use App\Classes\PayPalWrapper;
use App\Order;
use App\PayPalOrder;
use App\Services\PayPalOrderService;

class PayPalOrderController extends Controller
{
	public function init(PayPalOrderService $service, Order $order)
	{
		$service->initialize($order);

		return redirect()->route('orders.show', $order);
	}

	public function show(Order $order, $action = null)
	{
		// Order updated to some status
		if ($order->paid())
			return view('orders.order-success', compact('order'));

		// Redirect from PayPal
		if ($action === 'pending' && !$order->status())
			return view('orders.order-pending', compact('order'));

		// Missing any status
		if (!$order->status()) {
			$payUrl = $order->orderable->link;

			return view('orders.order-summary', compact('payUrl', 'order'));
		}

		return view('orders.order-error', compact('order'));
	}
}
