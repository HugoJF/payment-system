<?php

namespace App\Observers;

use App\Order;

class OrderObserver
{
    /**
     * Handle the order "creating" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function creating(Order $order)
    {
		$order->id = substr(md5(microtime(true)), 0, 5);
    }
}
