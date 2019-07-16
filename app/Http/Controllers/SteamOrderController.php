<?php

namespace App\Http\Controllers;

use App\Classes\SteamAccount;
use App\Classes\SteamID;
use App\Exceptions\MPEmptyResponseException;
use App\Order;
use App\SteamItem;
use App\SteamOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SteamOrderController extends Controller
{
	/**
	 * @param Order $order
	 *
	 * @return Order
	 * @throws \Exception
	 */
	public function init(Order $order)
	{
		// If this order is already initialized, return existing preference
		if ($order->orderable && $order->type() !== SteamOrder::class)
			throw new \Exception('You cannot reinitialize an order');
		else if ($order->orderable)
			return $order;

		// Check for tradelink
		if (empty($order->payer_tradelink))
			throw new \Exception('Unable to initialize order with Steam because it\'s missing the tradelink');

		/** @var SteamOrder $steamOrder */
		$steamOrder = SteamOrder::create();

		$steamOrder->base()->save($order);

		$order->save();
		$steamOrder->save();

		return $order;
	}

	public function execute(Request $request, Order $order)
	{
		throw new MPEmptyResponseException('Daemon is offline');

		if ($order->type() !== SteamOrder::class)
			throw new \Exception('Execution is only valid for Steam orders');

		$itemList = $request->input('items');

		$response = SteamAccount::sendTradeOffer($order->payer_tradelink, "Pedido com ID #{$order->id} para \"{$order->reason}\"", $itemList);

		$steamOrder = $order->orderable;

		$steamOrder->encoded_items = json_encode($itemList);

		$steamOrder->tradeoffer_id = $response['id'];
		$steamOrder->tradeoffer_state = $response['state'];
		$steamOrder->tradeoffer_sent_at = Carbon::now();

		$steamOrder->save();

		return $response;
		/*
		 * {#241
			  +"partner": {#247
				+"universe": 1
				+"type": 1
				+"instance": 1
				+"accountid": 73018255
			  }
			  +"id": "3557089384"
			  +"message": "Pedido com ID #5623b para VIP de 3 dias."
			  +"state": 2
			  +"itemsToGive": []
			  +"itemsToReceive": array:3 [
				0 => {#252
				  +"id": "14835802004"
				  +"assetid": "14835802004"
				  +"appid": 730
				  +"contextid": "2"
				  +"amount": 1
				}
				1 => {#250
				  +"id": "4618852404"
				  +"assetid": "4618852404"
				  +"appid": 730
				  +"contextid": "2"
				  +"amount": 1
				}
				2 => {#251
				  +"id": "4618852773"
				  +"assetid": "4618852773"
				  +"appid": 730
				  +"contextid": "2"
				  +"amount": 1
				}
			  ]
			  +"isOurOffer": true
			  +"created": "2019-05-03T06:37:14.559Z"
			  +"updated": "2019-05-03T06:37:14.559Z"
			  +"expires": "2019-05-17T06:37:14.559Z"
			  +"tradeID": null
			  +"fromRealTimeTrade": false
			  +"confirmationMethod": 0
			  +"escrowEnds": null
			  +"rawJson": ""
			}
		 */
	}

	/**
	 * @param $steamid
	 *
	 * @return static
	 * @throws \Exception
	 */
	public function inventory($steamid)
	{
		$response = SteamAccount::getInventory($steamid);

		$requestedItems = collect($response)->pluck('market_hash_name');

		if (SteamItem::count() === 0)
			throw new \Exception('Empty SteamItem table, fill it...');

		$requestedData = SteamItem::whereIn('market_hash_name', $requestedItems)->get();

		$requestedData = collect($requestedData)->mapWithKeys(function ($item) {
			return [$item['market_hash_name'] => $item];
		});

		$itemsArray = $requestedData->toArray();

		$response = collect($response)->reject(function ($item) use ($itemsArray, $requestedData) {
			return !array_key_exists($item['market_hash_name'], $itemsArray) ||
				$requestedData[ $item['market_hash_name'] ]->price < 0;
		});

		$response = collect($response)->map(function ($item) use ($requestedData) {
			$i = $requestedData[ $item['market_hash_name'] ];

			return [
				'appid'            => $item['appid'],
				'contextid'        => $item['contextid'],
				'assetid'          => $item['assetid'],
				'instanceid'       => $item['instanceid'],
				'market_hash_name' => $item['market_hash_name'],
				'price'            => $i->price,
				'icon_url'         => $i->icon_url,
			];
		});

		return $response;
	}
}
