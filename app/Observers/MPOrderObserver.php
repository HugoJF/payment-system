<?php

namespace App\Observers;

use App\Events\MPOrderUpdated;
use App\MPOrder;

class MPOrderObserver
{
    public function updated(MPOrder $order)
    {
        if ($order->wasRecentlyCreated) {
            return;
        }

        if ($order->isDirty()) {
            event(new MPOrderUpdated($order));
        }
    }
}
