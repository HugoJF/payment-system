<?php

namespace App\Events;

use App\Order;

interface OrderEvent
{
    // TODO: this can be an abtract class since each order is from OrderContract
    public function getBaseOrder();
}
