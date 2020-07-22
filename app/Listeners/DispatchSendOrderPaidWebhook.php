<?php

namespace App\Listeners;

use App\Events\OrderEvent;
use App\Jobs\SendOrderPaidWebhook;

class DispatchSendOrderPaidWebhook
{
    /**
     * Handle the event.
     *
     * @param OrderEvent $event
     *
     * @return void
     */
    public function handle(OrderEvent $event)
    {
        dispatch(new SendOrderPaidWebhook($event->getBaseOrder()));
    }
}
