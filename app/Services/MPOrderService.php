<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 7/17/2019
 * Time: 2:06 AM
 */

namespace App\Services;

use App\MPOrder;
use App\Order;
use App\Services\MP\MPOrderInitializationService;
use App\Services\MP\MPPreferenceDataService;
use App\Services\MP\MPRecheckService;

class MPOrderService
{
    public function initialize(Order $order)
    {
        /** @var MPOrderInitializationService $service */
        $service = app(MPOrderInitializationService::class);

        $service->handle($order);
    }

    /** @deprecated */
    public function generatePreferenceData(Order $order)
    {
        /** @var MPPreferenceDataService $service */
        $service = app(MPPreferenceDataService::class);

        return $service->handle($order);
    }

    /**
     * Get order amount that is accepted by MercadoPago. (converted from cents to R$)
     *
     * @param Order $order - price reference
     *
     * @return float
     * @deprecated
     */
    public function getAmount(Order $order)
    {
        /** @var ValueConversionService $service */
        $service = app(ValueConversionService::class);

        return $service->toMP($order->preset_amount);
    }

    /** @deprecated */
    public function recheckMPOrder(MPOrder $order)
    {
        /** @var MPRecheckService $service */
        $service = app(MPRecheckService::class);

        $service->handle($order);
    }
}
