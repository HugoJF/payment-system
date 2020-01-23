<?php

namespace App;

use App\Contracts\OrderContract;
use App\Services\MPOrderService;
use Illuminate\Database\Eloquent\Model;

class MPOrder extends Model implements OrderContract
{
	protected $table = 'mp_orders';

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

	public function recheck()
	{
	    /** @var MPOrderService $service */
	    $service = app(MPOrderService::class);

	    $service->recheckMPOrder($this);
	}

	public function paid()
	{
		return $this->paid_amount >= $this->amount;
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
