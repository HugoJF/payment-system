<?php

namespace App\Providers;

use App\Events\MPOrderPaid;
use App\Events\OrderPaid;
use App\Events\PayPalOrderPaid;
use App\Events\SteamOrderPaid;
use App\Listeners\DispatchSendOrderPaidWebhook;
use App\Listeners\TriggerBaseOrderPaid;
use Illuminate\Support\Facades\Event;
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
        MPOrderPaid::class => [
            TriggerBaseOrderPaid::class,
        ],
        PayPalOrderPaid::class => [
            TriggerBaseOrderPaid::class,
        ],
        SteamOrderPaid::class => [
            TriggerBaseOrderPaid::class,
        ],

        OrderPaid::class => [
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
