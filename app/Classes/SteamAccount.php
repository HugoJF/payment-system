<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 4/30/2019
 * Time: 7:57 PM
 */

namespace App\Classes;

use Illuminate\Support\Facades\Auth;
use Ixudra\Curl\Facades\Curl;

class SteamAccount
{

	public static $mocking = true;
	public static $saving = true;
	public static $responses = [];

	protected static function getPathByName($name)
	{
		return app_path('Mock/st/' . preg_replace('/[^A-Z0-9]/i', '-', $name));
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

	public static function mock($name, $json = false)
	{
		if ($json) {
			return json_decode(static::$responses[ $name ], true);
		} else {
			return static::$responses[ $name ];
		}
	}

	public static function curl($path, $data = [], $post = false)
	{
		if (static::$mocking)
			return static::mock(self::getPathByName($path));

		if ($post) {
			$token = '?token=' . config('steamaccount.token');
		} else {
			$data = array_merge($data, ['token' => config('steamaccount.token')]);
		}

		$query = Curl::to(config('steamaccount.api') . '/' . $path . ($token ?? ''));

		$query = $query->withData($data);

		$query->enableDebug('/home/vagrant/payment-system/storage/logs/curl.txt');

		$query = $query->asJson(true);

		if ($post) {
			$response = $query->post();
		} else {
			$response = $query->get();
		}

		if (!isset($response['error']) || !isset($response['response']) || $response['error'] == true)
			return false;

		if (static::$saving) {
			$file = self::getPathByName($path);

			self::writeToFile($file, $response['response']);
		}

		return $response['response'];
	}

	public static function status()
	{
		if (static::$mocking)
			return static::mock('status', true);

		return static::curl('status');
	}

	public static function getInventory($steamid)
	{
		if (static::$mocking)
			return static::mock('inventory', true);

		return static::curl('inventory', [
			'steamid' => $steamid,
		]);
	}

	public static function getInventoryFromAuthedUser()
	{
		$user = Auth::user();
		if ($user === false)
			return false;

		if (isset($user->tradelink)) {
			$inventory = static::getInventory($user->tradeid());
		} else {
			$inventory = static::getInventory($user->steamid);
		}

		return $inventory;
	}

	public static function cancelTradeOffer($tradeid)
	{
		if (static::$mocking)
			return static::mock('cancelTradeOffer', true);

		return static::curl('cancelTradeOffer', [
			'tradeid' => $tradeid,
		]);
	}

	public static function sendTradeOffer($tradelink, $message, $encoded_items)
	{
		if (static::$mocking)
			return static::mock('sendTradeOffer', true);

		$data = [
			'tradelink'     => $tradelink,
			'encoded_items' => $encoded_items,
			'message'       => $message,
		];

		return static::curl('sendTradeOffer', [
			'items' => json_encode($data),
		], true);
	}

	public static function getTradeOffer($tradeoffer_id)
	{
		if (static::$mocking)
			return static::mock('getTradeOffer', true);

		return static::curl('getTradeOffer', [
			'offerid' => $tradeoffer_id,
		]);
	}

	/**
	 * @deprecated - used to filter user inventory based on post parameters
	 *
	 * @param      $assetIdList
	 * @param      $inventory
	 *
	 * @return array|bool
	 */
	public static function fillItemArray($assetIdList, $inventory)
	{
		if ($inventory === false) {
			return false;
		}
		$mergedItemList = [];
		foreach ($assetIdList as $assetId) {
			foreach ($inventory as $item) {
				if ($item->assetid == $assetId) {
					$mergedItemList[] = $item;
					break;
				}
			}
		}

		return $mergedItemList;
	}
}