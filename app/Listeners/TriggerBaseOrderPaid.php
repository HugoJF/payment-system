<?php

namespace App\Listeners;

use App\Events\OrderEvent;
use App\Events\OrderPaid;
use App\Order;
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
        /** @var Order $order */
        $order = $event->getBaseOrder();

        event(new OrderPaid($order));
    }
}
