<?php

namespace App\Classes;

use Livepixel\MercadoPago\Facades\MP;

class MP2 extends MockableWrapper
{
	public static $saving = true;
	public static $mocking = true;
	public static $responses = [];

	protected static function getPathByName($name)
	{
		return app_path('Mock/mp/' . preg_replace('/[^A-Z0-9]/i', '-', $name));
	}

	protected static function writeToFile($path, $content)
	{
		$file = fopen($path, 'w');
		fwrite($file, json_encode($content));
		fclose($file);
	}

	protected static function readFromFile($path)
	{
		$file = fopen($path, 'r');
		$content = fread($file, filesize($path));
		fclose($file);

		return $content;
	}

	public static function fileMock($request, $fileName)
	{
		$path = self::getPathByName($fileName);

		$content = self::readFromFile($path);

		static::$responses[ $request ] = $content;
	}

	public static function saveResponse($name, $response)
	{
		if (static::$saving) {
			$path = self::getPathByName($name);

			self::writeToFile($path, $response);
		}

		return $response;
	}

	public static function _create_preference($preference)
	{
		if (static::$mocking)
			return static::mock('create_preference', true);

		return static::saveResponse('create_preference', \MP::create_preference($preference));
	}

	public static function get_payment($paymentId)
	{
		if (static::$mocking)
			return static::mock('get_payment');

		return static::saveResponse('get_payment', \MP::get_payment($paymentId));
	}

	public static function payments_search($key, $value)
	{
		if (static::$mocking)
			return static::mock('payments_search', true);

		return static::saveResponse('payments_search', self::get('v1', "payments/search?$key=$value"));
	}

	public static function get($type, $url)
	{
		if (static::$mocking)
			return static::mock('get' . $type);

		return static::saveResponse('get' . '/' . $type . '/' . $url, \MP::get('/' . $type . '/' . $url));
	}

	public static function mock($name, $json = false)
	{
		if ($json) {
			return json_decode(static::$responses[ $name ], true);
		} else {
			return static::$responses[ $name ];
		}
	}
}