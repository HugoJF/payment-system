<?php

namespace App\Http\Controllers;

use App\Classes\MP2;
use App\Order;
use App\Services\MPOrderService;

class MPOrderController extends Controller
{
	public function init(MPOrderService $service, Order $order)
	{
		$service->initialize($order);

		return redirect()->route('orders.show', $order);
	}

	public function show(Order $order, $action = null)
	{
		if ($order->paid())
			return view('orders.order-success', compact('order'));

		if ($action === 'pending')
			return view('orders.order-pending', compact('order'));

		if ($order->orderable->preference_id) {
			$preference = MP2::get_preference($order->orderable->preference_id);
			$payUrl = $preference['response']['init_point'];

			return view('orders.order-summary', compact('payUrl', 'order'));
		}

		return view('orders.order-error', compact('order'));
	}

}
