<?php

namespace App\Observers;

use App\Events\SteamOrderUpdated;
use App\SteamOrder;

class SteamOrderObserver
{
    public function updated(SteamOrder $order)
    {
        if ($order->wasRecentlyCreated) {
            return;
        }

        if ($order->isDirty()) {
            event(new SteamOrderUpdated($order));
        }
    }
}
