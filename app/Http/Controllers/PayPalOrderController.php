<?php

namespace App\Http\Controllers;

use App\Classes\PayPalWrapper;
use App\Order;
use App\PayPalOrder;

class PayPalOrderController extends Controller
{
	public function execute(Order $order)
	{
		if ($order->type() !== PayPalOrder::class)
			throw new \Exception('Execution is only valid for PayPal orders');

		$order->recheck();

		return $order;
	}

	/**
	 * @param Order $order
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function init(Order $order)
	{
		$type = $order->type();

		// Check if order was initialized
		if ($type && $type !== PayPalOrder::class)
			throw new \Exception('Order is already initialized by another processor.');

		// Create order database entries
		$ppOrder = new PayPalOrder();

		// Persist to database
		$ppOrderSaved = $ppOrder->save();

		// Check if details were saved
		if (!$ppOrderSaved)
			throw new \Exception('PayPal order details could not be saved');

		// Associate details
		$ppOrder->base()->save($order);
		$orderSaved = $order->save();

		// Check if base order was saved
		if (!$orderSaved)
			throw new \Exception('Base order could not be saved');

		// Process checkout cart
		$cart = PayPalOrder::getCheckoutCart($order);

		// Request PayPal checkout token
		$response = PayPalWrapper::setExpressCheckout($cart);

		// Check if response is valid
		if (!is_array($response))
			throw new \Exception('Invalid response from PayPal');

		// Check if token was returned
		if (!array_key_exists('TOKEN', $response))
			throw new \Exception('PayPal did not return a token');

		// Store token and base Order
		$ppOrder->token = $response['TOKEN'];
		$ppOrderSaved = $ppOrder->save();

		// Check if details were saved
		if (!$ppOrderSaved)
			throw new \Exception('Error while persisting PayPal order to database.');

		// Redirect user to PayPal
		return ['paypal_link' => $response['paypal_link']];
	}
}
