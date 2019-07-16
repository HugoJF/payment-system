<?php

namespace App;

use App\Classes\SteamAccount;
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
		$offer = SteamAccount::getTradeOffer($this->tradeoffer_id);

		if ($offer && array_key_exists('state', $offer)) {
			$this->tradeoffer_state = $offer['state'];
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
}
