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

        $changes = $order->getChanges();
        unset($changes['created_at']);
        unset($changes['updated_at']);

        if (count($changes) > 0) {
            event(new PayPalOrderUpdated($order));
        }
    }
}
