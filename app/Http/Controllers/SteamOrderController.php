<?php

namespace App\Http\Controllers;

use App\Classes\SteamAccount;
use App\Classes\SteamID;
use App\Order;
use App\ShopItem;
use App\SteamOrder;
use Illuminate\Http\Request;

class SteamOrderController extends Controller
{
	public function init(Order $order)
	{
		/** @var SteamOrder $steamOrder */
		$steamOrder = SteamOrder::create();

		$steamOrder->base()->save($order);

		$order->save();
		$steamOrder->save();

		return ['status' => 'initialized'];
	}

	public function execute(Request $request, Order $order)
	{
		// TODO: is this steam order?
		$itemList = $request->input('items');

		$duration = 3;

		$response = SteamAccount::sendTradeOffer($order->payer_tradelink, "Pedido com ID #{$order->public_id} para VIP de {$duration} dias.", $itemList);

		$steamOrder = $order->orderable;

		$steamOrder['encoded_items'] = json_encode($itemList);

		$steamOrder['tradeoffer_id'] = $response['id'];
		$steamOrder['tradeoffer_state'] = $response['state'];

		$steamOrder->save();

		// TODO: filter some shit?
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

	public function inventory($steamid)
	{
		$response = SteamAccount::getInventory($steamid);

		$requestedItems = collect($response)->pluck('market_hash_name');

		$requestedData = ShopItem::whereIn('market_hash_name', $requestedItems)->get();

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

		// TODO: this API is not wrapped in a response object
		return $response;
	}
}
