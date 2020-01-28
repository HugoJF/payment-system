<?php

namespace App\Listeners;

use App\Events\OrderPaid;
use App\Jobs\SendOrderPaidWebhook;

class DispatchSendOrderPaidWebhook
{
    /**
     * Handle the event.
     *
     * @param OrderPaid $event
     *
     * @return void
     */
    public function handle(OrderPaid $event)
    {
        dispatch(new SendOrderPaidWebhook($event->order));
    }
}
