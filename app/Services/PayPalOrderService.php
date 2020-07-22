<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 7/17/2019
 * Time: 2:39 AM
 */

namespace App\Services;

use App\Classes\PayPalWrapper;
use App\Events\PayPalOrderPaid;
use App\Order;
use App\PayPalOrder;
use Exception;

class PayPalOrderService
{

    /**
     * @param Order $order
     *
     * @return array
     */
    public static function getCheckoutCart(Order $order)
    {
        $id = $order->id;
        $prefix = config('paypal.invoice_prefix');
        $price = round($order->preset_amount / 100, 2);

        // Set checkout options
        $data['items'] = [[
            'name'  => $order->reason,
            'price' => $price,
            'qty'   => 1,
        ]];
        $data['invoice_id'] = "{$prefix}_{$id}";
        $data['invoice_description'] = "Pedido #$id";
        $data['return_url'] = route('orders.show', [$order, 'pending']);
        $data['cancel_url'] = route('orders.show', [$order, 'cancel']);
        $data['total'] = $price;

        return $data;
    }

    /**
     * @param $order
     *
     * @throws Exception
     */
    public function initialize($order)
    {
        // Create order database entries
        $ppOrder = PayPalOrder::create();

        // Associate details
        $ppOrder->base()->save($order);
        info('PayPal details saved');
        $order->save();
        info('Order details saved');

        // Process checkout cart
        $cart = $this->getCheckoutCart($order);

        // Request PayPal checkout token
        info('Setting ExpressCheckout.');
        $response = PayPalWrapper::setExpressCheckout($cart);
        info('ExpressCheckout set.', compact('response'));

        // Check if response is valid
        if (!is_array($response)) {
            info('Invalid response from PayPal', compact('response'));
            throw new Exception('Invalid response from PayPal');
        }

        // Check if token was returned
        if (!array_key_exists('TOKEN', $response)) {
            info('PayPal did not return a token', compact('response'));
            throw new Exception('PayPal did not return a token');
        }

        // Store token and base Order
        $ppOrder->token = $response['TOKEN'];
        $ppOrder->link = $response['paypal_link'];
        $ppOrder->save();
    }

    public function recheck(PayPalOrder $order)
    {
        // Check if order has any token associated with it
        if (!$order->token) {
            info(sprintf('Failed rechecking order %s that has no PayPal token.', $order->base->id));

            return;
        }

        // Check if PayPal has checkout details
        $response = PayPalWrapper::getExpressCheckoutDetails($order->token);

        // Check if response was successful
        if (!in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
            info('Failed to recheck PayPal order', compact('response'));

            return;
        }

        // Check if checkout has a payer
        if (!array_key_exists('PAYERID', $response))
            return;

        // If there is no transaction, and this code is reached, execute transaction
        if (!array_key_exists('TRANSACTIONID', $response)) {
            $service = app(PayPalOrderService::class);
            $response = PayPalWrapper::doExpressCheckoutPayment($service->getCheckoutCart($order->base), $order->token, $response['PAYERID']);
            info('DoExpressCheckoutPayment response', ['response' => $response]);
            $response = PayPalWrapper::getExpressCheckoutDetails($order->token);
        }

        // If no transaction
        if (!array_key_exists('TRANSACTIONID', $response))
            throw new Exception("There are no transaction ID associated with order {$order->base->id} TOKEN={$order->token}.");

        // Update order transaction
        $order->transaction_id = $response['TRANSACTIONID'];

        // Retrieve payment details
        $transaction = PayPalWrapper::getTransactionDetails($order->transaction_id);

        // Update database
        $order->status = $transaction['PAYMENTSTATUS'];

        // Update base order
        if ($order->paymentCompleted()) {
            $order->base->paid_amount = (int) (floatval($response['AMT']) * 100);
            $order->base->save();

            $order->touch();

            if($order->isDirty('paid_amount')) {
                event(new PayPalOrderPaid($order));
            }
        }

        // Only trigger saved event after base order is updated.
        $order->save();
    }
}
