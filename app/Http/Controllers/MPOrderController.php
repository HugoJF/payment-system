<?php

namespace App\Http\Controllers;

use App\Classes\MP2;
use App\Exceptions\AlreadyInitializedOrderException;
use App\Exceptions\MPResponseException;
use App\MPOrder;
use App\Order;

class MPOrderController extends Controller
{
	/*
	 * SELLER
	 * {
	 * 		"id":431836599,
	 * 		"nickname": "TESTTVXMHWM2",
	 * 		"password": "qatest7265",
	 * 		"site_status":"active",
	 * 		"email":"test_user_55075768@testuser.com"
	 * }
	 *
	 * BUYER
	 * {
	 * 		"id":431837628,
	 * 		"nickname": "TETE5281260",
	 * 		"password": "qatest657",
	 * 		"site_status":"active",
	 * 		"email":"test_user_93477566@testuser.com"
	 * }
	 */

	/**
	 * @param Order $order
	 *
	 * @return array
	 * @throws MPResponseException
	 */
	public function init(Order $order)
	{
		// If this order is already initialized, return existing preference
		if ($order->orderable)
			return MP2::get('checkout', 'preferences/' . $order->orderable->mp_preference_id)['response'];

		// Attempt to create a new MercadoPago preference
		$preference = MP2::create_preference($this->generatePreferenceData($order));

		// Check if response was sent
		if (!array_key_exists('response', $preference))
			throw new MPResponseException('Invalid MercadoPago API response');

		// Check if API successfully returned a response
		if (!array_key_exists('id', $preference['response']))
			throw new MPResponseException('MercadoPago API responded without a preference ID');

		// Prepare to store MercadoPago details to database
		$mpOrder = MPOrder::make();

		$mpOrder->mp_preference_id = $preference['response']['id'];
		$mpOrder->mp_amount = $this->getAmount($order);

		// Save first so we get an ID
		$mpOrder->save();

		// Associate details to base order
		$mpOrder->base()->save($order);

		$order->save();

		// Return MP preference details
		return $preference['response'];
	}

	public function execute(Order $order)
	{
		if ($order->type() !== MPOrder::class)
			throw new \Exception('Execution is only valid for MP orders');

		// Force rechecking
		$order->recheck();

		return $order;
	}

	protected function generatePreferenceData(Order $order)
	{
		$preferenceData = [
			'items'              => [[
				'title'       => $order->reason,
				'quantity'    => 1,
				'currency_id' => 'BRL',
				'unit_price'  => $this->getAmount($order),
			]],
			'back_urls'          => [
				'success' => url("orders/{$order->public_id}/pending"), // TODO: figure a better way to pass this
				'pending' => url("orders/{$order->public_id}/pending"), // TODO: figure a better way to pass this
				'failure' => url("orders/{$order->public_id}/failure"), // TODO: figure a better way to pass this
			],
			'auto_return'        => 'approved',
			'external_reference' => $order->public_id,
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
	protected function getAmount(Order $order)
	{
		return round($order->preset_amount / 100, 2);
	}
}
