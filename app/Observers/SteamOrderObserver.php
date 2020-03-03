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

        $changes = $order->getChanges();
        unset($changes['created_at']);
        unset($changes['updated_at']);

        if (count($changes) > 0) {
            event(new SteamOrderUpdated($order));
        }
    }
}
