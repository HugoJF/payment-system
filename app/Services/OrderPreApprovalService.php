<?php

namespace App\Services;

use App\Events\OrderPreApproved;
use App\Exceptions\OrderAlreadyApprovedException;
use App\Order;

class OrderPreApprovalService
{
    public function handle(Order $order)
    {
        if ($order->pre_approved_at) {
            throw new OrderAlreadyApprovedException;
        }

        $order->pre_approved_at = now();
        $order->save();

        event(new OrderPreApproved($order));
    }
}
