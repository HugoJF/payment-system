<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 7/17/2019
 * Time: 2:39 AM
 */

namespace App\Services;

use App\Order;
use App\PayPalOrder;
use App\Services\PayPal\PayPalCheckoutCartService;
use App\Services\PayPal\PayPalOrderInitializationService;
use App\Services\PayPal\PayPalRecheckService;
use Exception;

class PayPalOrderService
{
    /**
     * @param $order
     *
     * @throws Exception
     * @deprecated
     */
    public function initialize(Order $order)
    {
        /** @var PayPalOrderInitializationService $service */
        $service = app(PayPalOrderInitializationService::class);

        return $service->handle($order);
    }

    /**
     * @param Order $order
     *
     * @return array
     * @deprecated
     */
    public static function getCheckoutCart(Order $order)
    {
        /** @var PayPalCheckoutCartService $service */
        $service = app(PayPalCheckoutCartService::class);

        return $service->handle($order);
    }

    /**
     * @param PayPalOrder $order
     *
     * @throws Exception
     * @deprecated
     */
    public function recheck(PayPalOrder $order)
    {
        /** @var PayPalRecheckService $service */
        $service = app(PayPalRecheckService::class);

        return $service->handle($order);
    }
}
