<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 5/16/2019
 * Time: 11:08 PM
 */

namespace App\Classes;

use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Services\ExpressCheckout;

class PayPalWrapper extends MockableWrapper
{
	public static $folder = 'pp';
	public static $exceptionWhenMockIsNotFound = false;

	/**
	 * @var ExpressCheckout
	 */
	protected static $instance;

	public static function getInstance()
	{
		if (is_null(static::$instance))
			static::$instance = new ExpressCheckout();

		return static::$instance;
	}

	public static function _setExpressCheckout($cart)
	{
		Log::info("_setExpressCheckout called");
		$response = static::getInstance()->setExpressCheckout($cart);
		Log::info("_setExpressCheckout responded");

		return $response;
	}

	public static function _getExpressCheckoutDetails($token)
	{
		Log::info("_getExpressCheckoutDetails called");
		$response = static::getInstance()->getExpressCheckoutDetails($token);
		Log::info("_getExpressCheckoutDetails responded");

		return $response;
	}

	public static function _doExpressCheckoutPayment($cart, $token, $payerId)
	{
		Log::info("_doExpressCheckoutPayment called");
		$response = static::getInstance()->doExpressCheckoutPayment($cart, $token, $payerId);
		Log::info("_doExpressCheckoutPayment responded");

		return $response;
	}

	public static function _getTransactionDetails($transactionId)
	{
		Log::info("_getTransactionDetails called");
		$response = static::getInstance()->getTransactionDetails($transactionId);
		Log::info("_getTransactionDetails responded");

		return $response;
	}
}