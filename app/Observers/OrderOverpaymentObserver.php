<?php

namespace App\Observers;

use App\Events\OrderOverpaid;
use App\Order;

class OrderOverpaymentObserver
{
    public function updated(Order $order)
    {
        $oldPaymentAmount = $order->getOriginal('paid_amount');
        $newPaymentAmount = $order->paid_amount;
        $wasPaid = $oldPaymentAmount > 0;

        $paidAmountChanged = $newPaymentAmount > $oldPaymentAmount;

        if ($wasPaid && $paidAmountChanged) {
            event(new OrderOverpaid($order));
        }
    }
}
