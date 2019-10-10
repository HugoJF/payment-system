<?php

namespace App\Forms;

use App\MPOrder;
use App\Order;
use App\PayPalOrder;
use App\SteamOrder;
use Kris\LaravelFormBuilder\Form;

class OrderForm extends Form
{
	private $map = [
		MPOrder::class     => [
			'id'   => Order::MERCADOPAGO,
			'form' => MPOrderForm::class,
		],
		PayPalOrder::class => [
			'id'   => Order::PAYPAL,
			'form' => PayPalOrderForm::class,
		],
		SteamOrder::class  => [
			'id'   => Order::STEAM,
			'form' => SteamOrderForm::class,
		],
	];

	public function buildForm()
	{
		$this->addBaseOrderFields();
		$this->addSpecificFields();
	}

	private function addBaseOrderFields(): void
	{
		$this->add('reason', 'textarea');
		$this->add('product_name_singular', 'text');
		$this->add('product_name_plural', 'text');
		$this->add('preset_amount', 'number');
		$this->add('paid_amount', 'number');
		$this->add('unit_price', 'number');
		$this->add('unit_price_limit', 'number');
		$this->add('discount_per_unit', 'number');
	}

	private function addSpecificFields(): void
	{
		$order = $this->getData('order');
		$type = $order->type();

		if ($type) {
			$info = $this->map[ $type ];

			$this->add('orderable', 'form', [
				'class'       => $info['form'],
				'formOptions' => [
					'model' => $order->orderable,
				],
			]);
		}
	}
}
