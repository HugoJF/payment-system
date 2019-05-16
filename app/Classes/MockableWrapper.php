<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 5/16/2019
 * Time: 5:42 PM
 */

namespace App\Classes;

class MockableWrapper
{
	public static $saving = true;
	public static $mocking = true;
	public static $responses = [];
	public static $folder = 'misc';

	protected static function startMocking()
	{
		static::$mocking = true;
	}

	protected static function stopMocking()
	{
		static::$mocking = false;
	}

	protected static function startSaving()
	{
		static::$saving = true;
	}

	protected static function stopSaving()
	{
		static::$saving = false;
	}

	protected static function getPathByName($name)
	{
		$folder = static::$folder;

		return app_path("Mock/$folder/" . preg_replace('/[^A-Z0-9]/i', '-', $name));
	}

	protected static function writeToFile($name, $content)
	{
		$file = fopen(self::getPathByName($name), 'w');
		fwrite($file, json_encode($content));
		fclose($file);
	}

	protected static function readFromFile($name)
	{
		$path = self::getPathByName($name);
		$file = fopen($path, 'r');
		$content = fread($file, filesize($path));
		fclose($file);

		return $content;
	}

	public static function mockByFile($request, $fileName)
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

	/**
	 * @param $name
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	protected static function getMockedResponse($name)
	{
		if (array_key_exists($name, static::$responses)) {
			return static::$responses[ $name ];
		} else {
			throw new \Exception("Trying to mock response $name but a mocked response was not set.");
		}
	}

	public static function __callStatic($name, $arguments)
	{
		if (static::$mocking) {
			return static::getMockedResponse($name);
		}

		$method = "_$name";
		$function = "static::$method";

		if (method_exists(static::class, $method)) {
			$response = forward_static_call($function, $arguments);
			static::saveResponse($name, $response);

			return $response;
		} else {
			throw new \Exception("Function $function does not exists");
		}
	}
}