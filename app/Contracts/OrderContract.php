<?php

namespace App\Contracts;

use App\Order;

interface OrderContract
{
	public function recheck();

	public function paid();

	public function units(Order $order);

	public function paidUnits(Order $order);

	public function status();

	public function type();

	public function canInit(Order $order);
}