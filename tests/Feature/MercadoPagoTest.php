<?php

namespace Tests\Feature;

use App\Classes\MP2;
use App\MPOrder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class MercadoPagoTest extends TestCase
{
	use DatabaseTransactions;

	public function getMpOrderData()
	{
		return [
			'mp_preference_id' => '431836599-963adbca-e670-4175-aa49-7fbd78209636',
			'mp_amount'        => 5,
			'mp_paid_amount'   => 0,
			'mp_order_id'      => null,
			'mp_order_status'  => null,
		];
	}

	public function testMercadoPagoCanBeUsedToBuySomething()
	{
		MP2::fileMock('create_preference', 'create_preference');
		MP2::fileMock('payments_search', 'payments_search');

		$creation = $this->_testMPOrderCreation();
		$this->_testMPOrderInitialization($creation);
		$this->_testMPOrderExecution($creation);
	}

	protected function _testMPOrderCreation()
	{
		$creation = $this->post(route('orders.store'), $this->getBaseOrderData());

		$creation->assertStatus(201);

		$this->assertDatabaseHas('orders', $this->getBaseOrderData());

		return $creation;
	}

	protected function _testMPOrderInitialization($creation)
	{
		$initialization = $this->get(route('orders.mp-init', $creation->json('public_id')));

		dd($initialization->json());

		$initialization->assertStatus(200);

		$this->assertDatabaseHas('mp_orders', $this->getMpOrderData());
		$this->assertDatabaseHas('orders', array_merge($this->getBaseOrderData(), [
			'orderable_type' => MPOrder::class,
			'orderable_id'   => 1,
		]));
	}

	protected function _testMPOrderExecution($creation)
	{
		$execution = $this->get(route('orders.mp-execute', $creation->json('public_id')));

		$this->assertDatabaseHas('mp_orders', array_merge($this->getMpOrderData(), [
			'mp_paid_amount' => 5,
			'mp_order_id' => 1107903898,
		]));

		$execution->json();
	}
}
