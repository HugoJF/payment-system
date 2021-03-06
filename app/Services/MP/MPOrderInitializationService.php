<?php

namespace App\Services\MP;

use App\Classes\MP2;
use App\MPOrder;
use App\Order;

class MPOrderInitializationService
{
    /**
     * @var MPPreferenceDataService
     */
    protected MPPreferenceDataService $preferenceDataService;

    public function __construct(MPPreferenceDataService $preferenceDataService)
    {
        $this->preferenceDataService = $preferenceDataService;
    }

    public function handle(Order $order)
    {
        // Attempt to create a new MercadoPago preference
        $preference = MP2::create_preference($this->preferenceDataService->handle($order));

        // Prepare to store MercadoPago details to database
        $mpOrder = new MPOrder;

        $mpOrder->preference_id = $preference['response']['id'];

        // Save first so we get an ID
        $mpOrder->save();

        // Associate details to base order
        $mpOrder->base()->save($order);
        $order->save();
    }
}
