<?php

namespace App\Classes;

use Illuminate\Support\Facades\Auth;
use Ixudra\Curl\CurlService;
use Ixudra\Curl\CurlServiceProvider;
use Ixudra\Curl\Facades\Curl;

class SteamAccount extends MockableWrapper
{
	public static $folder = 'st';
	public static $exceptionWhenMockIsNotFound = false;

	/**
	 * @param       $path
	 * @param array $data
	 * @param bool  $post
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public static function curl($path, $data = [], $post = false)
	{
		$token = config('steamaccount.token');
		$apiUrl = config('steamaccount.api');

		if ($post) {
			$token = "?token=$token";
		} else {
			$data['token'] = $token;
		}

		$query = Curl::to("$apiUrl/$path" . ($post ? $token : ''));

		$query->withData($data);

		$query->withHeader('Accept', 'application/json');
		$query->returnResponseObject();
		$query->enableDebug(storage_path('logs/curl.txt'));

		$query->asJson(true);

		if ($post) {
			$response = $query->post();
		} else {
			$response = $query->get();
		}

		$content = $response->content;

		if (!in_array($response->status, [200, 201]))
			throw new \Exception($response->error);

		if (!isset($content['error']) || !isset($content['response']) || $content['error'] === true)
			throw new \Exception($content['message']);

		return $content['response'];
	}

	public static function status()
	{
		if (static::$mocking)
			return static::mock('status', true);

		return static::curl('status');
	}

	public static function _getInventory($steamid)
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

	public static function _sendTradeOffer($tradelink, $message, $encoded_items)
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

	public static function _getTradeOffer($tradeoffer_id)
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