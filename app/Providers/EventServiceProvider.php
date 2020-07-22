<?php

namespace App\Providers;

use App\Events\MPOrderPaid;
use App\Events\MPOrderUpdated;
use App\Events\MPOrderUpdating;
use App\Events\OrderOverpaid;
use App\Events\OrderPaid;
use App\Events\OrderUpdated;
use App\Events\PayPalOrderPaid;
use App\Events\PayPalOrderUpdated;
use App\Events\PayPalOrderUpdating;
use App\Events\SteamOrderPaid;
use App\Events\SteamOrderUpdated;
use App\Events\SteamOrderUpdating;
use App\Listeners\CheckOverpayment;
use App\Listeners\DispatchSendOrderPaidWebhook;
use App\Listeners\TriggerBaseOrderPaid;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        MPOrderPaid::class     => [
            TriggerBaseOrderPaid::class,
        ],
        PayPalOrderPaid::class => [
            TriggerBaseOrderPaid::class,
        ],
        SteamOrderPaid::class  => [
            TriggerBaseOrderPaid::class,
        ],

        OrderPaid::class    => [
            DispatchSendOrderPaidWebhook::class,
        ],
        OrderOverpaid::class => [
            DispatchSendOrderPaidWebhook::class,
        ],

        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        //
    }
}
