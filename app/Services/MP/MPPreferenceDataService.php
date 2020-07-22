<?php

namespace App\Services\MP;

use App\Order;

class MPPreferenceDataService
{
    public function handle(Order $order)
    {
        return [
            'items'              => [[
                'title'       => $order->reason,
                'quantity'    => 1,
                'currency_id' => 'BRL',
                'unit_price'  => $this->getAmount($order),
            ]],
            'back_urls'          => [
                'success' => route('orders.show', $order, 'pending'),
                'pending' => route('orders.show', $order, 'pending'),
                'failure' => route('orders.show', $order, 'failure'),
            ],
            'auto_return'        => 'approved',
            'external_reference' => config('mercadopago.reference_prefix') . $order->id,
            'notification_url'   => route(config('ipn.mp-notifications-route')),
        ];
    }
}
