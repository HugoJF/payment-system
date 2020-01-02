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
use Exception;
use Illuminate\Support\Facades\Log;

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

    public function recheckMPOrder(MPOrder $order)
    {
        if (empty($order->order_id)) {
            Log::warning("Rechecking MPOrder {$order->id} without 'order_id'.");
            $this->searchForPayments($order);

            return;
        }

        // Order checking is deprecated as it's somewhat inconsistent (and requires more requests)

        // Query API for information
        $order = MP2::get('merchant_orders', $order->order_id);

        if (!is_array($order))
            throw new Exception('Non array response returned');

        // Check if response was valid
        if (!array_key_exists('status', $order))
            throw new Exception('MercadoPago API returned empty response without status.');

        // Check if response was OK
        if ($order['status'] != 200)
            throw new Exception("MercadoPago API returned with status: {$order['status']}");

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
        if (empty($order->preference_id))
            $order->preference_id = $response['preference_id'];

        // Update order status
        $order->paid_amount = $paidAmount;

        // Update base order
        $order->base->paid_amount = round($paidAmount * 100);
        $order->base->save();

        // Save
        $order->touch();
        $order->save();

        // Log
        Log::info("Order rechecked with total paid amount: R$ {$order->original['paid_amount']} -> R$ {$order->paid_amount}");
	}

    protected function searchForPayments(MPOrder $order)
    {
        $response = MP2::payments_search('external_reference', config('mercadopago.reference_prefix') . $order->base->id);

        // Check for status in response
        if (!is_array($response) || !array_key_exists('status', $response))
            throw new Exception('Missing status from response');

        // Check if response is 200
        if ($response['status'] !== 200)
            throw new Exception('Non-200 response');

        // Check if response has a response key
        if (!array_key_exists('response', $response))
            throw new Exception('Missing response key from response');

        $response = $response['response'];

        if (!array_key_exists('results', $response))
            throw new Exception('Missing results from response');

        $results = collect($response['results']);

        Log::info("Found {$results->count()} results while searching for payments with external reference: #{$order->id}");

        $orders = $results->pluck('order.id');

        // If there
        if ($orders->count() === 1)
            $order->order_id = $orders->first();

        // Sum approved payments
        $paidAmount = $results->reduce(function ($paid, $payment) {
            if ($payment['status'] !== 'approved')
                return $paid;

            return $paid + round($payment['transaction_amount']); // This is R$ and should not be converted to cents.
        }, 0);

        // Update order
        $order->base->paid_amount = $paidAmount * 100;
        $order->paid_amount = $paidAmount;

        $order->base->save();
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
