<?php

namespace App;

use App\Classes\PayPalWrapper;
use App\Contracts\OrderContract;
use App\Exceptions\PayPalCommunicationException;
use App\Exceptions\PayPalNoPayerIdException;
use App\Exceptions\PayPalNoTransactionIdException;
use App\Services\PayPalOrderService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Services\ExpressCheckout;

class PayPalOrder extends Model implements OrderContract
{
	protected $table = 'paypal_orders';

	protected $fillable = ['status'];

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
			throw new \Exception('Error while communicating with PayPal.');

		// Check if checkout has a payer
		if (!array_key_exists('PAYERID', $response))
			return;
		//			throw new PayPalNoPayerIdException('Order does not have a PayerID associated with it!');

		// If there is no transaction, and this code is reached, execute transaction
		if (!array_key_exists('TRANSACTIONID', $response)) {
			$service = app(PayPalOrderService::class);
			$response = PayPalWrapper::doExpressCheckoutPayment($service->getCheckoutCart($this->base), $this->token, $response['PAYERID']);
			Log::info('DoExpressCheckoutPayment response', ['response' => $response]);
			$response = PayPalWrapper::getExpressCheckoutDetails($this->token);
		}

		// If no transaction
		if (!array_key_exists('TRANSACTIONID', $response))
			throw new \Exception("There are no transaction ID associated with order {$this->base->id} TOKEN={$this->token}.");

		// Update order transaction
		$this->transaction_id = $response['TRANSACTIONID'];

		// Retrieve payment details
		$paymentDetails = PayPalWrapper::getTransactionDetails($this->transaction_id);
		$status = $paymentDetails['PAYMENTSTATUS'];

		// Update base order
		if ($this->paid()) {
			$this->base->paid_amount = $this->base->preset_amount;
			$this->base->save();
		}

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

	public function canInit(Order $order)
	{
		return true;
	}

	public function units(Order $order)
	{
		return null;
	}

	public function paidUnits(Order $order)
	{
		return null;
	}
}
