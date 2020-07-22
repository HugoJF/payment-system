<?php

namespace App\Services\PayPal;

use App\Classes\PayPalWrapper;
use App\Exceptions\InvalidResponseException;
use App\Exceptions\MissingTokenException;
use App\Order;
use App\PayPalOrder;
use Exception;
use Illuminate\Support\Facades\DB;

class PayPalOrderInitializationService
{
    /**
     * @var PayPalCheckoutCartService
     */
    protected PayPalCheckoutCartService $cartService;

    public function __construct(PayPalCheckoutCartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function handle(Order $order): PayPalOrder
    {
        return DB::transaction(fn () => $this->initialize($order));
    }

    public function initialize(Order $order): PayPalOrder
    {
        // Process checkout cart
        $cart = $this->cartService->handle($order);

        // Request PayPal checkout token
        $response = PayPalWrapper::setExpressCheckout($cart);
        info('ExpressCheckout set.', compact('response'));

        // Check if response is valid
        if (!is_array($response)) {
            info('Invalid response from PayPal', compact('response'));
            throw new InvalidResponseException;
        }

        // Check if token was returned
        if (!array_key_exists('TOKEN', $response)) {
            info('PayPal did not return a token', compact('response'));
            throw new MissingTokenException;
        }

        // Create order database entries
        $ppOrder = new PayPalOrder;

        // Store token and base Order
        $ppOrder->token = $response['TOKEN'];
        $ppOrder->link = $response['paypal_link'];
        $ppOrder->save();

        $order->orderable()->associate($ppOrder);
        $order->save();

        return $ppOrder;
    }
}
