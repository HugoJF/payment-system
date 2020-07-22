<?php

namespace App\Observers;

use App\Events\OrderOverpaid;
use App\Events\OrderPaid;
use App\Order;

class OrderPaymentObserver
{
    public function updated(Order $order)
    {
        $wasPending = $order->getOriginal('paid_amount') === 0;
        $isPaid = $order->paid_amount >= $order->preset_amount;


        if ($wasPending && $isPaid) {
            event(new OrderPaid($order));
        }
    }
}
