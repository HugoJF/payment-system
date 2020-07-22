<?php

namespace App\Http\Controllers;

use App\Order;
use App\Services\PayPalOrderService;

class PayPalOrderController extends Controller
{
    public function init(PayPalOrderService $service, Order $order)
    {
        info("Initializing order $order->id as PayPalOrder");
        $service->initialize($order);

        return redirect()->route('orders.show', $order);
    }

    public function show(Order $order, $action = null)
    {
        // Redirect from PayPal
        if ($action === 'pending' && !$order->orderable->status) {
            return view('orders.order-pending', compact('order'));
        }

        // Missing any status
        if (!$order->orderable->status) {
            $payUrl = $order->orderable->link;

            return view('orders.order-summary', compact('payUrl', 'order'));
        }

        return view('orders.order-error', compact('order'));
    }
}
