<?php

namespace App\Http\Controllers;

use App\Classes\MP2;
use App\Order;
use App\OrderService;
use App\Services\MPOrderService;
use Illuminate\Http\Request;
use Symplify\EasyCodingStandard\SniffRunner\Exception\File\NotImplementedException;

class MPOrderController extends Controller
{
    public function init(MPOrderService $service, Order $order)
    {
        $service->initialize($order);

        return redirect()->route('orders.show', $order);
    }

    public function show(Order $order, $action = null)
    {
        if ($action === 'pending') {
            return view('orders.order-pending', compact('order'));
        }

        if ($order->orderable->preference_id) {
            $preference = MP2::get_preference($order->orderable->preference_id);
            $payUrl = $preference['response']['init_point'];

            return view('orders.order-summary', compact('payUrl', 'order'));
        }

        return view('orders.order-error', compact('order'));
    }

    public function ipn(MPOrderService $service, Request $request)
    {
        $id = $request->query('id');
        $topic = $request->query('topic');

        info("IPN from MercadoPago: id=$id topic=$topic");

        if ($topic !== 'payment') {
            return response()->json([]);
        }

        $payment = $service->findPayment($id);

        $externalReference = $payment['external_reference'];
        info("Found payment with external reference: $externalReference");

        $id = preg_replace(config('mercadopago.reference_prefix'), '', $externalReference);
        info("Cleaned external reference to $id");

        /** @var Order $order */
        $order = Order::find($id);

        $order->recheck();

        return response()->json([]);
    }

}
