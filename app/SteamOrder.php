<?php

namespace App;

use App\Classes\SteamAccount;
use App\Services\SteamOrderService;
use Illuminate\Database\Eloquent\Model;
use App\Contracts\OrderContract;

class SteamOrder extends Model implements OrderContract
{
    protected $table = 'steam_orders';

    protected $dates = ['tradeoffer_sent_at'];

    protected $casts = [
        'items' => 'array',
    ];

    /**********
     * STATES *
     **********/

    public const INVALID = 1;
    public const ACTIVE = 2;
    public const ACCEPTED = 3;
    public const COUNTERED = 4;
    public const EXPIRED = 5;
    public const CANCELED = 6;
    public const DECLINED = 7;
    public const INVALID_ITEMS = 8;
    public const CREATED_NEEDS_CONFIRMATION = 9;
    public const CANCELED_BY_SECOND_FACTOR = 10;
    public const IN_ESCROW = 11;

    /*****************
     * RELATIONSHIPS *
     *****************/

    public function base()
    {
        return $this->morphOne('App\Order', 'orderable');
    }

    public function getEncodedItemsAttribute($value)
    {
        return json_decode($value, true);
    }

    /********************
     * CUSTOM FUNCTIONS *
     ********************/

    public function cancel()
    {
        SteamAccount::cancelTradeOffer($this->tradeoffer_id);
        $this->recheck();
    }

    /**************
     * OVERWRITES *
     **************/

    public function recheck()
    {
        /** @var SteamOrderService $service */
        $service = app(SteamOrderService::class);

        return $service->recheckSteamOrder($this);
    }

    public function accepted()
    {
        return $this->tradeoffer_state === static::ACCEPTED;
    }

    public function type()
    {
        return self::class;
    }

    public function canInit($order)
    {
        return isset($order->payer_tradelink);
    }

    public function fixedPricing()
    {
        return false;
    }
}
