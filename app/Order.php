<?php

namespace App;

use App\Contracts\OrderContract;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer       $preset_amount
 * @property integer       $unit_price
 * @property integer       $unit_price_limit
 * @property string        $public_id
 * @property string        $return_url
 * @property string        $cancel_url
 * @property mixed         $reason
 * @property OrderContract $orderable
 * @property string        payer_tradelink
 */
class Order extends Model implements OrderContract
{
	protected $with = ['orderable'];

	protected $appends = ['paid', 'type'];

	protected $fillable = [
		'reason',
		'return_url',
		'cancel_url',
		'preset_amount',
		'payer_steam_id',
		'payer_tradelink',
		'unit_price',
		'unit_price_limit',
		'discount_per_unit',
		'min_units',
		'max_units',
	];

	public function orderable()
	{
		return $this->morphTo();
	}

	public function recheck()
	{
		if ($this->orderable)
			return $this->orderable->recheck();
		else
			return false;
	}

	public function getPaidAttribute()
	{
		return $this->paid();
	}

	public function paid()
	{
		if ($this->orderable)
			return $this->orderable->paid();
		else
			return false;
	}

	public function status()
	{
		if ($this->orderable)
			return $this->orderable->status();
		else
			return false;
	}

	public function getTypeAttribute()
	{
		return $this->type();
	}

	public function type()
	{
		if ($this->orderable)
			return $this->orderable->type();
		else
			return false;
	}
}
