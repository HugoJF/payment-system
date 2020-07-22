<?php

namespace App\Observers;

use App\Events\PayPalOrderUpdated;
use App\PayPalOrder;

class PayPalOrderObserver
{
    public function updated(PayPalOrder $order)
    {
        if ($order->wasRecentlyCreated) {
            return;
        }

        if ($order->isDirty()) {
            event(new PayPalOrderUpdated($order));
        }
    }
}
