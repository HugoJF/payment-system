<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 7/17/2019
 * Time: 2:39 AM
 */

namespace App\Services;

use App\Classes\PayPalWrapper;
use App\Order;
use App\PayPalOrder;
use Illuminate\Support\Facades\Log;

class PayPalOrderService
{

	/**
	 * @param Order $order
	 *
	 * @return array
	 */
	public static function getCheckoutCart(Order $order)
	{
		$order_id = $order->id;

		// Set checkout options
		$data['items'] = [[
			'name'  => $order->reason,
			'price' => round($order->preset_amount / 100, 2),
			'qty'   => 1,
		]];
		$data['invoice_id'] = config('paypal.invoice_prefix') . '_' . $order_id;
		$data['invoice_description'] = "Pedido #$order_id";
		$data['return_url'] = route('orders.show', [$order, 'pending']);
		$data['cancel_url'] = url("orders/{$order->id}/cancel");

		// Calculate total price
		$total = collect($data['items'])->reduce(function ($total, $item) {
			return $total + $item['price'] * $item['qty'];
		}, 0);

		// Update price on preferences
		$data['total'] = round($total, 2);

		return $data;
	}

	/**
	 * @param $order
	 *
	 * @throws \Exception
	 */
	public function initialize($order)
	{
		// Create order database entries
		$ppOrder = PayPalOrder::create();

		// Associate details
		$ppOrder->base()->save($order);
		Log::info('PayPal details saved');
		$order->save();
		Log::info('Order details saved');

		// Process checkout cart
		$cart = $this->getCheckoutCart($order);

		// Request PayPal checkout token
		Log::info('Setting ExpressCheckout.');
		$response = PayPalWrapper::setExpressCheckout($cart);
		Log::info('ExpressCheckout set.', compact('response'));

		// Check if response is valid
		if (!is_array($response))
			throw new \Exception('Invalid response from PayPal');

		// Check if token was returned
		if (!array_key_exists('TOKEN', $response))
			throw new \Exception('PayPal did not return a token');

		// Store token and base Order
		$ppOrder->token = $response['TOKEN'];
		$ppOrder->link = $response['paypal_link'];
		$ppOrder->save();

	}
}