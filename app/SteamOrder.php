<?php

namespace App;

use App\Classes\SteamAccount;
use App\Events\TradeofferUpdated;
use App\Services\SteamOrderService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Contracts\OrderContract;

/**
 * @property integer tradeoffer_state
 * @property integer tradeoffer_id
 * @property Carbon  tradeoffer_sent_at
 */
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

    public function getItemsAttribute()
    {
        return json_decode($this->items);
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
        if (!isset($this->tradeoffer_id))
            return;

        $offer = SteamAccount::getTradeOffer($this->tradeoffer_id);

        if ($offer && array_key_exists('state', $offer))
            $this->tradeoffer_state = $offer['state'];

        if ($this->paid()) {
            $service = app(SteamOrderService::class);
            $this->base->paid_amount = $service->getItemsValue($this->items);
            $this->base->save();
        }

        $this->touch();

        $this->save();

        return $offer;
    }

    public function paid()
    {
        return $this->tradeoffer_state === static::ACCEPTED;
    }

    public function status()
    {
        return $this->tradeoffer_state;
    }

    public function type()
    {
        return self::class;
    }

    public function canInit(Order $order)
    {
        return isset($order->payer_tradelink);
    }

    public function units(Order $base)
    {
        return $this->calculateUnits($base, $base->preset_amount);
    }

    public function paidUnits(Order $base)
    {
        return $this->calculateUnits($base, $base->paid_amount);
    }

    protected function calculateUnits(Order $base, $value)
    {
        $perUnit = $base->unit_price;
        $units = 0;

        while ($value >= $perUnit) {
            $units++;
            $value -= $perUnit;
            $perUnit -= $base->discount_per_unit;

            if ($perUnit < $base->unit_price_limit)
                $perUnit = $base->unit_price_limit;
        }

        return $units;
    }

}
