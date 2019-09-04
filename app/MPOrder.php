<?php

namespace App;

use App\Classes\MP2;
use App\Contracts\OrderContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class MPOrder extends Model implements OrderContract
{
	protected $table = 'mp_orders';

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
	 * @throws MPEmptyResponseException
	 * @throws MPResponseException
	 * @throws \Exception - if API response was invalid
	 */
	public function recheck()
	{
		if (empty($this->order_id)) {
			Log::warning("Rechecking MPOrder {$this->id} without 'order_id'.");
			$this->searchForPayments();

			return;
		}

		// Order checking is deprecated as it's somewhat inconsistent (and requires more requests)

		// Query API for information
		$order = MP2::get('merchant_orders', $this->order_id);

		if (!is_array($order))
			throw new \Exception('Non array response returned');

		// Check if response was valid
		if (!array_key_exists('status', $order))
			throw new \Exception('MercadoPago API returned empty response without status.');

		// Check if response was OK
		if ($order['status'] != 200)
			throw new \Exception("MercadoPago API returned with status: {$order['status']}");

		// Check if we have a response
		if (!array_key_exists('response', $order) || empty($order['response']))
			throw new \Exception("MercadoPago API returned empty response");

		// Keep response reference
		$response = $order['response'];

		// Compute total amount of each payment in this order
		$paidAmount = collect($response['payments'])->reduce(function ($total, $item) {
			if (!array_key_exists('status', $item) || $item['status'] !== 'approved') {
				return $total;
			}

			return $total + round($item['total_paid_amount'], 2);
		}, 0);

		// Update preference ID if it's not present
		if (empty($this->preference_id))
			$this->preference_id = $response['preference_id'];

		// Update order status
		$this->paid_amount = $paidAmount;

		// Update base order
		$this->base->paid_amount = round($paidAmount * 100);
		$this->base->save();

		// Save
		$this->touch();
		$this->save();

		// Log
		Log::info("Order rechecked with total paid amount: R$ {$this->original['paid_amount']} -> R$ {$this->paid_amount}");
	}

	/**
	 * @throws \Exception
	 */
	protected function searchForPayments()
	{
		$response = MP2::payments_search('external_reference', config('mercadopago.reference_prefix') . $this->base->id);

		// Check for status in response
		if (!is_array($response) || !array_key_exists('status', $response))
			throw new MPEmptyResponseException('Missing status from response');

		// Check if response is 200
		if ($response['status'] !== 200)
			throw new MPResponseException('Non-200 response');

		// Check if response has a response key
		if (!array_key_exists('response', $response))
			throw new MPResponseException('Missing response key from response');

		$response = $response['response'];

		if (!array_key_exists('results', $response))
			throw new MPResponseException('Missing results from response');

		$results = collect($response['results']);

		Log::info("Found {$results->count()} results while searching for payments with external reference: #{$this->id}");

		$orders = $results->pluck('order.id');

		// If there
		if ($orders->count() === 1)
			$this->order_id = $orders->first();

		// Sum approved payments
		$paidAmount = $results->reduce(function ($paid, $payment) {
			if ($payment['status'] !== 'approved')
				return $paid;

			return $paid + round($payment['transaction_amount']); // This is R$ and should not be converted to cents.
		}, 0);

		// Update order
		$this->paid_amount = $paidAmount;

		$this->save();
	}

	public function paid()
	{
		return $this->paid_amount >= $this->amount;
	}

	public function status()
	{
		return $this->paid() ? 'paid' : 'pending';
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
