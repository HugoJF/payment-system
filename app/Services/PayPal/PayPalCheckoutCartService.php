<?php

namespace App\Services\PayPal;

use App\Order;
use App\Services\PayPalOrderService;
use App\Services\ValueConversionService;

class PayPalCheckoutCartService
{
    /**
     * @var ValueConversionService
     */
    protected $conversionService;

    public function __construct(ValueConversionService $conversionService)
    {
        $this->conversionService = $conversionService;
    }

    public function handle(Order $order)
    {
        $id = $order->id;
        $prefix = config('paypal.invoice_prefix');
        $price = $this->conversionService->toPayPal($order->preset_amount);

        // Set checkout options
        $data['items'] = [[
            'name'  => $order->reason,
            'price' => $price,
            'qty'   => 1,
        ]];
        $data['invoice_id'] = join('_', [$prefix, $id]);
        $data['invoice_description'] = "Pedido #$id";
        $data['return_url'] = route('orders.show', [$order, 'pending']);
        $data['cancel_url'] = route('orders.show', [$order, 'cancel']);
        $data['total'] = $price;

        return $data;
    }
}
