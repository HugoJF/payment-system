<?php

namespace App\Contracts;

use App\Order;

interface OrderContract
{
	public function recheck();

	public function units(Order $order);

	public function paidUnits(Order $order);

	public function type();

	public function canInit($order);
}
