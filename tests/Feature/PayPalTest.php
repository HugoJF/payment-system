<?php

namespace Tests\Feature;

use App\Classes\PayPalWrapper;
use App\PayPalOrder;
use Tests\TestCase;

class PayPalTest extends TestCase
{
	private $creation;

	public function testSteamCanBeUsedToBuySomething()
	{
		PayPalWrapper::startMocking();
		PayPalWrapper::mockByFile('setExpressCheckout', 'setExpressCheckout');
		PayPalWrapper::mockByFile('getExpressCheckoutDetails', 'getExpressCheckoutDetails');
		PayPalWrapper::mockByFile('doExpressCheckoutPayment', 'doExpressCheckoutPayment');

		$this->_testPpOrderCreation();
		$this->_testPpOrderInitialization();

		PayPalWrapper::mockByFile('getExpressCheckoutDetails', 'getExpressCheckoutDetails-2');

		$this->_testPpOrderExecution();
	}

	protected function _testPpOrderCreation()
	{
		$this->creation = $this->post(route('orders.store'), $this->getBaseOrderData());

		$this->creation->assertStatus(201);

		$this->assertDatabaseHas('orders', $this->getBaseOrderData());
	}

	protected function _testPpOrderInitialization()
	{
		$initialization = $this->get(route('orders.paypal-init', $this->creation->json('id')));

		$initialization->assertStatus(200);

		$this->assertDatabaseHas('orders', array_merge($this->getBaseOrderData(), [
			'orderable_type' => PayPalOrder::class,
			'orderable_id'   => 1,
		]));
	}

	protected function _testPpOrderExecution()
	{
		$execution = $this->get(route('orders.paypal-execute', $this->creation->json('id')));

		$execution->assertStatus(200);

		$this->assertDatabaseHas('paypal_orders', [
			'status' => 'Completed',
		]);

		$execution->json();
	}
}