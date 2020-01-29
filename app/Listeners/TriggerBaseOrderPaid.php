<?php

namespace App\Listeners;

use App\Events\OrderEvent;
use App\Events\OrderPaid;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TriggerBaseOrderPaid
{
    /**
     * Handle the event.
     *
     * @param object $event
     *
     * @return void
     */
    public function handle(OrderEvent $event)
    {
        $order = $event->getBaseOrder();

        event(new OrderPaid($order));
    }
}
