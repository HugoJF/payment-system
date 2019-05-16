<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

	protected function getBaseOrderData()
	{

		$data['reason'] = 'VIPzão bonito do servidor do de_nerd';
		$data['return_url'] = 'https://denerdtv.com';
		$data['cancel_url'] = 'https://denerdtv.com/';
		$data['preset_amount'] = 465;

		$data['payer_steam_id'] = '76561198033283983';
		$data['payer_tradelink'] = 'https://steamcommunity.com/tradeoffer/new/?partner=73018255&token=iFVJ4YIQ';

		$data['unit_price'] = 35;
		$data['unit_price_limit'] = 20;
		$data['discount_per_unit'] = 0.26;
		$data['min_units'] = 15;
		$data['max_units'] = 90;

		return $data;
	}
}
