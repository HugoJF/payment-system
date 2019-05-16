<?php

namespace App\Contracts;


interface OrderContract
{
	public function recheck();

	public function paid();

	public function status();

	public function type();
}