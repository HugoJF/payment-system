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

        $changes = $order->getChanges();
        unset($changes['created_at']);
        unset($changes['updated_at']);

        if (count($changes) > 0) {
            event(new MPOrderUpdated($order));
        }
    }
}
