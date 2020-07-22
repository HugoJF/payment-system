<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderService;
use App\Services\SteamOrderService;
use App\SteamOrder;
use Exception;
use Illuminate\Http\Request;

class SteamOrderController extends Controller
{
    /**
     * @param SteamOrderService $service
     * @param Order             $order
     *
     * @return mixed
     * @throws Exception
     */
    public function init(SteamOrderService $service, Order $order)
    {
        if (empty($order->payer_tradelink)) {
            return redirect()->route('orders.show', $order);
        }

        $service->initialize($order);

        return redirect()->route('orders.show', $order);
    }

    public function execute(SteamOrderService $service, Request $request, Order $order)
    {
        // Decode items (they are passed as json strings)
        $items = array_map(function ($item) {
            return json_decode($item, true);
        }, $request->input('items'));

        try {
            $service->execute($order, $items);
        } catch (Exception $e) {
            flash()->error($e->getMessage());

            return back();
        }

        return redirect()->route('orders.show', $order);
    }

    public function show(SteamOrderService $service, Order $order)
    {
        if ($order->orderable->tradeoffer_state === SteamOrder::ACTIVE && !$order->orderable->tradeoffer_id) {
            return view('orders.order-pending', compact('order'));
        }

        if ($order->orderable->tradeoffer_state === SteamOrder::ACTIVE) {
            return view('orders.order-tradeoffer', [
                'order'        => $order,
                'tradeofferId' => $order->orderable->tradeoffer_id,
            ]);
        }

        if (!$order->orderable->tradeoffer_sent_at) {
            return view('inventory', [
                'color' => 'blue',
                'width' => 'w-1/2',
                'items' => $service->getInventory($order->payer_steam_id),
                'order' => $order,
            ]);
        }

        return view('orders.order-error', compact('order'));
    }
}
