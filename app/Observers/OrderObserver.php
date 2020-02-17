<?php

namespace App\Observers;

use App\Events\OrderPaid;
use App\Events\OrderUpdated;
use App\Order;
use Illuminate\Support\Str;

class OrderObserver
{
    /**
     * Handle the order "creating" event.
     *
     * @param \App\Order $order
     *
     * @return void
     */
    public function creating(Order $order)
    {
        $order->id = Str::random(5);
    }

    public function updated(Order $order)
    {
        if ($order->wasRecentlyCreated) {
            return;
        }

        $changes = $order->getChanges();
        unset($changes['created_at']);
        unset($changes['updated_at']);

        if (count($changes) > 0) {
            event(new OrderUpdated($order));
        }
    }
}
