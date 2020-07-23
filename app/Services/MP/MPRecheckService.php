<?php

namespace App\Services\MP;

use App\Classes\MP2;
use App\Exceptions\InvalidResponseException;
use App\MPOrder;
use App\Services\ValueConversionService;
use Exception;

class MPRecheckService
{
    /**
     * @var ValueConversionService
     */
    protected ValueConversionService $conversionService;

    public function __construct(ValueConversionService $conversionService)
    {
        $this->conversionService = $conversionService;
    }

    public function handle(MPOrder $mpOrder)
    {
        $payments = $this->fetchPayments($mpOrder->base->id);

        $this->recheck($mpOrder, $payments);
    }

    protected function fetchPayments(string $id): array
    {
        $payments = MP2::payments_search('external_reference', config('mercadopago.reference_prefix') . $id);
        logger()->warning("Rechecking MPOrder {$id}");

        // Check for status in response
        if (!is_array($payments) || !array_key_exists('status', $payments)) {
            info('Invalid response from MP', compact('payments'));
            throw new InvalidResponseException;
        }

        // Check if response is 200
        if (!in_array($payments['status'], [200, 201])) {
            info('Unexpected response code' . $payments['status'], compact('payments'));
            throw new InvalidResponseException;
        }

        // Check if response has a response key
        if (!array_key_exists('response', $payments)) {
            throw new InvalidResponseException;
        }

        $payments = $payments['response'];

        if (!array_key_exists('results', $payments)) {
            throw new InvalidResponseException;
        }

        $count = count($payments);
        info("Found {$count} payments while searching with external reference: #{$id}");

        return $payments['results'];
    }

    protected function recheck(MPOrder $order, array $payments)
    {
        // Sum approved payments in R$ (not cents)
        $paidAmount = $this->calculatePaidAmount($payments);

        // Update order
        $order->base->paid_amount = $this->conversionService->fromMP($paidAmount);
        $order->base->save();
    }

    protected function calculatePaidAmount(array $payments)
    {
        return collect($payments)->reduce(function ($paid, $payment) {
            $id = $payment['id'];
            $status = $payment['status'];

            info("--- Payment $id status: $status");
            if ($status !== 'approved') {
                return $paid;
            }

            return $paid + floatval($payment['transaction_amount']); // This is R$ and should not be converted to cents.
        }, 0);
    }
}
