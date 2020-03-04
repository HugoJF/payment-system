<?php

namespace App\Contracts;

interface OrderContract
{
	public function recheck();

	public function type();

	public function canInit($order);
}
