<?php

namespace App\Services\Forms;

use App\Forms\OrderForm;
use App\Order;

class OrderForms extends BaseForm
{
	public function edit(Order $order)
	{
		return $this->builder->create(OrderForm::class, [
			'method' => 'PATCH',
			'url'    => route('admin.orders.update', $order),
			'model'  => $order,
		], compact('order'));
	}
}