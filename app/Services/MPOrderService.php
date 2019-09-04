<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 7/17/2019
 * Time: 2:06 AM
 */

namespace App\Services;

use App\Classes\MP2;
use App\MPOrder;
use App\Order;

class MPOrderService
{
	public function initialize(Order $order)
	{
		// Attempt to create a new MercadoPago preference
		$preference = MP2::create_preference($this->generatePreferenceData($order));

		// Prepare to store MercadoPago details to database
		$mpOrder = MPOrder::make();

		$mpOrder->preference_id = $preference['response']['id'];
		$mpOrder->amount = $this->getAmount($order);

		// Save first so we get an ID
		$mpOrder->save();

		// Associate details to base order
		$mpOrder->base()->save($order);
		$order->save();
	}

	public function generatePreferenceData(Order $order)
	{
		$preferenceData = [
			'items'              => [[
				'title'       => $order->reason,
				'quantity'    => 1,
				'currency_id' => 'BRL',
				'unit_price'  => $this->getAmount($order),
			]],
			'back_urls'          => [
				'success' => route('orders.show', $order, 'pending'),
				'pending' => route('orders.show', $order, 'pending'),
				'failure' => route('orders.show', $order, 'failure'),
			],
			'auto_return'        => 'approved',
			'external_reference' => config('mercadopago.reference_prefix') . $order->id,
			'notification_url'   => route(config('ipn.mp-notifications-route')),
		];

		return $preferenceData;
	}

	/**
	 * Get order amount that is accepted by MercadoPago. (converted from cents to R$)
	 *
	 * @param Order $order - price reference
	 *
	 * @return float
	 */
	public function getAmount(Order $order)
	{
		return round($order->preset_amount / 100, 2);
	}

}