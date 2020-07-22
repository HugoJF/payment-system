<?php

namespace App;

use App\Contracts\OrderContract;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Order extends Model implements OrderContract
{
    public const PAYPAL = 'paypal';
    public const MERCADOPAGO = 'mp';
    public const STEAM = 'steam';

    public $incrementing = false;

    protected $with = ['orderable'];

    protected $appends = ['units', 'paid_units', 'paid', 'type', 'init_point'];

    protected $dates = [
        'webhooked_at', 'webhook_attempted_at',
    ];

    protected $fillable = [
        'reason',
        'return_url',
        'cancel_url',
        'webhook_url',
        'view_url',
        'preset_amount',
        'preset_units',
        'payer_steam_id',
        'payer_tradelink',
        'unit_price',
        'unit_price_limit',
        'discount_per_unit',
        'avatar',
        'min_units',
        'max_units',
        'product_name_singular',
        'product_name_plural',
    ];

    public function webhooks()
    {
        return $this->hasMany(WebhookHistory::class);
    }

    public function orderable()
    {
        return $this->morphTo();
    }

    public function scopeUnpaid(Builder $query)
    {
        $query->whereColumn('paid_amount', '<', 'preset_amount');
    }

    public function scopePaid(Builder $query)
    {
        $query->whereColumn('paid_amount', '>=', 'preset_amount');
    }

    public function scopeLessRechecksThan(Builder $query, int $maxRechecks)
    {
        $query->where('recheck_attempts', '<', $maxRechecks);
    }

    public function getPaidUnitsAttribute()
    {
        /** @var OrderService $service */
        $service = app(OrderService::class);

        if ($this->fixedPricing()) {
            return $this->preset_units * ($this->paid_amount / $this->preset_amount);
        } else {
            return $service->calculateUnits($this, $this->paid_amount);
        }
    }

    public function getUnitsAttribute()
    {
        /** @var OrderService $service */
        $service = app(OrderService::class);

        if ($this->fixedPricing()) {
            return $this->preset_units;
        } else {
            return $service->calculateUnits($this, $this->preset_amount);
        }
    }

    public function getOverPaidAttribute()
    {
        return $this->paid_amount > $this->preset_amount;
    }

    public function getPaidAttribute()
    {
        return $this->paid_amount >= $this->preset_amount;
    }

    public function getTypeAttribute()
    {
        return $this->type();
    }

    public function getInitPointAttribute()
    {
        return route('orders.show', $this);
    }

    public function canInit($type)
    {
        /** @var OrderService $service */
        $service = app(OrderService::class);

        $class = $service->getClassByType($type);

        if ($class) {
            $c = app($class);

            return $c->canInit($this);
        } else {
            return false;
        }
    }

    public function recheck()
    {
        if ($this->orderable) {
            $this->increment('recheck_attempts');

            return $this->orderable->recheck();
        } else {
            return false;
        }
    }

    public function type()
    {
        if ($this->orderable)
            return $this->orderable->type();
        else
            return false;
    }

    public function fixedPricing()
    {
        if ($this->orderable)
            return $this->orderable->fixedPricing();
        else
            return null;
    }

    public function canComputeUnits()
    {
        return !is_null($this->preset_units);
    }
}
