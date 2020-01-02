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

	public function paid()
	{
		return collect(['Completed', 'Processed'])->contains(function ($value) {
			return strcasecmp($this->status, $value) === 0;
		});
	}

	public function status()
	{
		return $this->status;
	}

	public function type()
	{
		return self::class;
	}

	public function canInit($order)
	{
		return true;
	}

	public function units(Order $order)
	{
		return null;
	}

	public function paidUnits(Order $order)
	{
		return null;
	}
}
