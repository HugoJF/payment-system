<?php

namespace App\Http\Controllers;

use App\MPOrder;
use App\Order;
use App\PayPalOrder;
use Srmklive\PayPal\Services\ExpressCheckout;

class PayPalOrderController extends Controller
{
	/**
	 * @var ExpressCheckout
	 */
	protected $provider;

	public function __construct()
	{
		$this->provider = new ExpressCheckout();
	}

	public function execute(Order $order)
	{
		if ($order->type() !== PayPalOrder::class)
			throw new \Exception('Execution is only valid for PayPal orders');

		$order->recheck();

		return ['status' => $order->status()]; // TODO: this should return the order?
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

		// TODO: why check for $type?
		if ($type && $type !== PayPalOrder::class) {
			throw new \Exception('Order is already initialized by another processor.');
		}

		// Create order database entries
		$ppOrder = new PayPalOrder();

		// Persist to database
		$ppOrder->save();

		// Associate details
		$ppOrder->base()->save($order);
		$order->save();

		// Process checkout cart
		$cart = PayPalOrder::getCheckoutCart($order);

		// Request PayPal checkout token
		$response = $this->provider->setExpressCheckout($cart);

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
