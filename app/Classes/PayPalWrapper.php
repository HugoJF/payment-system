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

		return static::getInstance()->setExpressCheckout($cart);
	}

	public static function _getExpressCheckoutDetails($token)
	{
		Log::info("_getExpressCheckoutDetails called");

		return static::getInstance()->getExpressCheckoutDetails($token);
	}

	public static function _doExpressCheckoutPayment($cart, $token, $payerId)
	{
		Log::info("_doExpressCheckoutPayment called");

		return static::getInstance()->doExpressCheckoutPayment($cart, $token, $payerId);
	}

	public static function _getTransactionDetails($transactionId)
	{
		Log::info("_getTransactionDetails called");

		return static::getInstance()->getTransactionDetails($transactionId);
	}
}