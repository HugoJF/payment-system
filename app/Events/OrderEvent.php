<?php

namespace App\Events;

use App\Order;

abstract class OrderEvent
{
    protected $order;

    public function setBaseOrder(Order $order)
    {
        $this->order = $order;
    }

    public function getBaseOrder()
    {
        return $this->order;
    }
}
