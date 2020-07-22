<?php

namespace App;

use App\Contracts\OrderContract;
use App\Services\PayPalOrderService;
use Illuminate\Database\Eloquent\Model;

class PayPalOrder extends Model implements OrderContract
{
	protected $table = 'paypal_orders';

	protected $fillable = ['status'];

	/*****************
	 * RELATIONSHIPS *
	 *****************/

	public function base()
	{
		return $this->morphOne('App\Order', 'orderable');
	}

    public function paymentCompleted()
    {
        return collect(['Completed', 'Processed'])->contains(function ($value) {
            return strcasecmp($this->status, $value) === 0;
        });
    }

	/**************
	 * OVERWRITES *
	 **************/

	/**
	 * @throws \Exception
	 */
	public function recheck()
	{
	    /** @var PayPalOrderService $service */
	    $service = app(PayPalOrderService::class);

	    $service->recheck($this);
	}

	public function type()
	{
		return self::class;
	}

	public function canInit($order)
	{
		return true;
	}

    public function fixedPricing()
    {
        return true;
    }
}
