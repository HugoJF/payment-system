<?php

namespace Tests\Feature;

use App\Classes\SteamAccount;
use App\SteamOrder;
use Tests\TestCase;

class SteamTest extends TestCase
{
	private $creation;

	public function testSteamCanBeUsedToBuySomething()
	{
		SteamAccount::startMocking();
		SteamAccount::mockByFile('getTradeOffer', 'getTradeOffer');
		SteamAccount::mockByFile('inventory', 'inventory');
		SteamAccount::mockByFile('sendTradeOffer', 'sendTradeOffer');

		$this->_testStOrderCreation();
		$this->_testStOrderInitialization();
		$this->_testStOrderExecution();
		$this->_testStOrderExecuted();
	}

	protected function _testStOrderCreation()
	{
		$this->creation = $this->post(route('orders.store'), $this->getBaseOrderData());

		$this->creation->assertStatus(201);

		$this->assertDatabaseHas('orders', $this->getBaseOrderData());
	}

	protected function _testStOrderInitialization()
	{
		$initialization = $this->get(route('orders.steam-init', $this->creation->json('id')));

		$initialization->assertStatus(200);

		$this->assertDatabaseHas('orders', array_merge($this->getBaseOrderData(), [
			'orderable_type' => SteamOrder::class,
			'orderable_id'   => 1,
		]));
	}

	protected function _testStOrderExecution()
	{
		$execution = $this->post(route('orders.steam-execute', $this->creation->json('id')), $this->getSteamData());

		$execution->assertStatus(200);

		$this->assertDatabaseHas('steam_orders', [
			'encoded_items' => '[{"assetid":"14835801990","appid":730,"contextid":"2"}]',
		]);

		$execution->json();
	}

	protected function _testStOrderExecuted()
	{
		$this->artisan('steamorders:refresh');
		$executed = $this->get(route('orders.show', $this->creation->json('id')));

		$executed->assertStatus(200);

		$this->assertDatabaseHas('steam_orders', [
			'tradeoffer_id'    => 3571498682,
			'tradeoffer_state' => SteamOrder::ACCEPTED,
		]);
	}

	private function getSteamData()
	{
		return [
			'items' => [[
				'assetid'   => "14835801990",
				'appid'     => 730,
				'contextid' => "2",
			]],
		];
	}
}