<?php

namespace App\Observers;

use App\Order;
use Illuminate\Support\Str;

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
		$order->id = Str::random(5);
    }
}
