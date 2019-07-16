<?php

namespace App\Classes;

class MP2 extends MockableWrapper
{
	public static $folder = 'mp';
	public static $exceptionWhenMockIsNotFound = false;

	public static function _create_preference($preference)
	{
		return \MP::create_preference($preference);
	}

	public static function _get_payment($paymentId)
	{
		return \MP::get_payment($paymentId);
	}

	public static function _payments_search($key, $value)
	{
		return self::get('v1', "payments/search?$key=$value");
	}

	public static function _get($type, $url)
	{
		return \MP::get('/' . $type . '/' . $url);
	}
}