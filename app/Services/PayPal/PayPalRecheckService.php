<?php

namespace App\Services\PayPal;

use App\Classes\PayPalWrapper;
use App\PayPalOrder;
use App\Services\ValueConversionService;
use Exception;

class PayPalRecheckService
{
    /**
     * @var ValueConversionService
     */
    protected ValueConversionService $conversionService;

    /**
     * @var PayPalCheckoutCartService
     */
    protected PayPalCheckoutCartService $cartService;

    public function __construct(ValueConversionService $conversionService, PayPalCheckoutCartService $cartService)
    {
        $this->conversionService = $conversionService;
        $this->cartService = $cartService;
    }

    public function handle(PayPalOrder $ppOrder): void
    {
        // Check if order has any token associated with it
        if (!$ppOrder->token) {
            info(sprintf('Failed rechecking order %s that has no PayPal token.', $ppOrder->base->id));

            return;
        }

        // Check if PayPal has checkout details
        $response = PayPalWrapper::getExpressCheckoutDetails($ppOrder->token);

        // Check if response was successful
        if (!in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
            info('Failed to recheck PayPal order', compact('response'));

            return;
        }

        // Check if checkout has a payer
        if (!array_key_exists('PAYERID', $response)) {
            return;
        }

        // If there is no transaction, and this code is reached, execute transaction
        if (!array_key_exists('TRANSACTIONID', $response)) {
            $response = PayPalWrapper::doExpressCheckoutPayment($this->cartService->handle($ppOrder->base), $ppOrder->token, $response['PAYERID']);
            info('DoExpressCheckoutPayment response', ['response' => $response]);
            $response = PayPalWrapper::getExpressCheckoutDetails($ppOrder->token);
            info('getExpressCheckoutDetails response', ['response' => $response]);
        }

        // If no transaction
        if (!array_key_exists('TRANSACTIONID', $response)) {
            throw new Exception("There are no transaction ID associated with order {$ppOrder->base->id} TOKEN={$ppOrder->token}.");
        }

        $this->refreshTransactionDetails($ppOrder, $response);
    }

    protected function refreshTransactionDetails(PayPalOrder $ppOrder, array $details)
    {
        $paidAmount = 0;
        $transactions = [];

        for ($n = 0; $n < 10; $n++) {
            $idKey = "PAYMENTREQUEST_{$n}_TRANSACTIONID";
            $amtKey = "PAYMENTREQUEST_{$n}_AMT";

            if (!array_key_exists($idKey, $details) || !array_key_exists($amtKey, $details)) {
                continue;
            }

            $amount = floatval($details[$amtKey]);
            $transactions[] = $details[$idKey];

            $paidAmount += $this->conversionService->fromPayPal($amount);
        }

        // Update database
        $ppOrder->status = $details['CHECKOUTSTATUS'];
        $ppOrder->transaction_id = join('|', $transactions);

        $ppOrder->save();

        // Update base order
        if ($ppOrder->paymentCompleted()) {
            $ppOrder->base->paid_amount = $paidAmount;
            $ppOrder->base->save();
        }

    }
}
