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
        if (empty($order->payer_tradelink))
            throw new Exception('Unable to initialize order with Steam because it\'s missing the tradelink');

        $service->initialize($order);

        return redirect()->route('orders.show', $order);
    }

    public function execute(OrderService $orderService, SteamOrderService $service, Request $request, Order $order)
    {
        // Decode items (they are passed as json strings)
        $items = array_map(function ($item) {
            return json_decode($item, true);
        }, $request->input('items'));

        $receivedValue = $service->getItemsValue($items);
        $paidItems = $orderService->calculateUnits($order, $receivedValue);

        if ($paidItems < $order->min_units) {
            flash()->error("Pedido abaixo da quantidade mínima de $order->min_units!");

            return redirect()->route('orders.show', $order);
        }

        try {
            $service->execute($order, $items);
        } catch (Exception $e) {
            flash()->error($e->getMessage());
        }

        return redirect()->route('orders.show', $order);
    }

    public function show(SteamOrderService $service, Order $order)
    {
        if ($order->status() === SteamOrder::ACCEPTED)
            return view('orders.order-success', compact('order'));

        if ($order->status() === SteamOrder::ACTIVE) {
            $tradeofferId = $order->orderable->tradeoffer_id;

            return view('orders.order-pending', compact('order', 'tradeofferId'));
        }

        if (!$order->orderable->tradeoffer_sent_at) {
            $items = $service->getInventory($order->payer_steam_id);

            return view('inventory', [
                'color' => 'blue',
                'width' => 'w-1/2',
                'items' => $items,
                'order' => $order,
            ]);
        }

        return view('orders.order-error', compact('order'));
    }
}
