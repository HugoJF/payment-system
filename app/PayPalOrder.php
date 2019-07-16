<?php

namespace App;

use App\Classes\PayPalWrapper;
use App\Contracts\OrderContract;
use App\Exceptions\PayPalCommunicationException;
use App\Exceptions\PayPalNoPayerIdException;
use App\Exceptions\PayPalNoTransactionIdException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Services\ExpressCheckout;

/**
 * @property string $pp_status
 * @property string $token
 * @property string $transaction_id
 * @property Order  $base
 * @property string $status
 */
class PayPalOrder extends Model implements OrderContract
{
	protected $table = 'paypal_orders';

	/*****************
	 * RELATIONSHIPS *
	 *****************/

	public function base()
	{
		return $this->morphOne('App\Order', 'orderable');
	}

	/**************
	 * OVERWRITES *
	 **************/

	/**
	 * @throws \Exception
	 */
	public function recheck()
	{
		// Check if PayPal has checkout details
		$response = PayPalWrapper::getExpressCheckoutDetails($this->token);

		// Check if response was successful
		if (!in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING']))
			throw new PayPalCommunicationException('Error while communicating with PayPal.');

		// Check if checkout has a payer
		if (!array_key_exists('PAYERID', $response))
			throw new PayPalNoPayerIdException('Order does not have a PayerID associated with it!');

		// If there is no transaction, and this code is reached, execute transaction
		if (!array_key_exists('TRANSACTIONID', $response)) {
			$response = PayPalWrapper::doExpressCheckoutPayment(self::getCheckoutCart($this->base), $this->token, $response['PAYERID']);
			Log::info('DoExpressCheckoutPayment response', ['response' => $response]);
			$response = PayPalWrapper::getExpressCheckoutDetails($this->token);
		}

		// If no transaction
		if (!array_key_exists('TRANSACTIONID', $response))
			throw new PayPalNoTransactionIdException("There are no transaction ID associated with order {$this->base->id} TOKEN={$this->token}.");

		// Update order transaction
		$this->transaction_id = $response['TRANSACTIONID'];

		// Retrieve payment details
		$paymentDetails = PayPalWrapper::getTransactionDetails($this->transaction_id);
		$status = $paymentDetails['PAYMENTSTATUS'];

		// Update database
		$this->status = $status;
		$this->save();
	}

	public function paid()
	{
		return collect(['Completed', 'Processed'])->contains(function ($value) {
			return strcasecmp($this->status, $value) === 0;
		});
	}

	public function status()
	{
		return $this->status;
	}

	public function type()
	{
		return self::class;
	}

	/**************
	 * OVERWRITES *
	 **************/

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
		$data['return_url'] = url("orders/{$order->id}/pending");
		$data['cancel_url'] = url("orders/{$order->id}/cancel");

		// Calculate total price
		$total = collect($data['items'])->reduce(function ($total, $item) {
			return $total + $item['price'] * $item['qty'];
		}, 0);

		// Update price on preferences
		$data['total'] = round($total, 2);

		return $data;
	}
}
