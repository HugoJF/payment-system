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
use DB;
use Exception;
use Illuminate\Support\Collection;

class MPOrderService
{
    public function initialize(Order $order)
    {
        // Attempt to create a new MercadoPago preference
        $preference = MP2::create_preference($this->generatePreferenceData($order));

        // Prepare to store MercadoPago details to database
        $mpOrder = new MPOrder;

        $mpOrder->preference_id = $preference['response']['id'];

        // Save first so we get an ID
        $mpOrder->save();

        // Associate details to base order
        $mpOrder->base()->save($order);
        $order->save();
    }

    public function generatePreferenceData(Order $order)
    {
        return [
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

    public function recheckMPOrder(MPOrder $order)
    {
        logger()->warning("Rechecking MPOrder {$order->id}");

        $response = MP2::payments_search('external_reference', config('mercadopago.reference_prefix') . $order->base->id);

        // Check for status in response
        if (!is_array($response) || !array_key_exists('status', $response)) {
            throw new Exception('Missing status from response');
        }

        // Check if response is 200
        if ($response['status'] !== 200) {
            throw new Exception('Non-200 response');
        }

        // Check if response has a response key
        if (!array_key_exists('response', $response)) {
            throw new Exception('Missing response key from response');
        }

        $response = $response['response'];

        if (!array_key_exists('results', $response)) {
            throw new Exception('Missing results from response');
        }

        $results = collect($response['results']);

        info("Found {$results->count()} payments while searching with external reference: #{$order->base->id}");

        // Sum approved payments in R$ (not cents)
        $paidAmount = $this->calculatePaymentsPaidAmount($results);

        // Update order
        $order->base->paid_amount = $paidAmount * 100;
        $order->base->save();
    }

    protected function calculatePaymentsPaidAmount(Collection $payments)
    {
        return $payments->reduce(function ($paid, $payment) {
            $id = $payment['id'];
            $status = $payment['status'];

            info("--- payment $id is status: $status");
            if ($status !== 'approved') {
                return $paid;
            }

            return $paid + round($payment['transaction_amount'], 2); // This is R$ and should not be converted to cents.
        }, 0);
    }

}
