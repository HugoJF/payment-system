<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 7/17/2019
 * Time: 2:55 AM
 */

namespace App\Services;

use App\Classes\SteamAccount;
use App\Order;
use App\OrderService;
use App\SteamItem;
use App\SteamOrder;
use Carbon\Carbon;
use DB;
use Exception;

class SteamOrderService
{
    public function initialize(Order $order)
    {
        $steamOrder = SteamOrder::create();

        $steamOrder->base()->save($order);
        $order->save();
    }

    // TODO: check what's the actual return value of this function
    public function recheckSteamOrder(SteamOrder $order)
    {
        if (!isset($order->tradeoffer_id))
            return;

        $offer = SteamAccount::getTradeOffer($order->tradeoffer_id);

        if (!$offer) {
            return;
        }

        if ($offer && array_key_exists('state', $offer)) {
            $order->tradeoffer_state = $offer['state'];
        }

        if ($order->accepted()) {
            $order->base->paid_amount = $order->base->preset_amount;
        }

        $order->touch();
        $order->base->touch();

        $order->save();
        $order->base->save();

        return $offer;
    }

    public function execute(Order $order, $items)
    {
        /** @var OrderService $orderService */
        $orderService = app(OrderService::class);

        $value = $this->getItemsValue($order->payer_steam_id, $items);
        $paidItems = $orderService->calculateUnits($order, $value);

        if ($paidItems < $order->min_units) {
            throw new Exception("Pedido abaixo da quantidade mínima de $order->min_units!");
        }

        // Send tradeoffer
        $response = SteamAccount::sendTradeOffer($order->payer_tradelink, "Pedido #{$order->id} para \"{$order->reason}\"", $items);

        DB::beginTransaction();
        try {
            // Update the order amount
            $order->preset_amount = $value;

            // Update order details
            $steamOrder = $order->orderable;
            $steamOrder->encoded_items = json_encode((array) $items);
            $steamOrder->tradeoffer_id = $response['id'];
            $steamOrder->tradeoffer_state = $response['state'];
            $steamOrder->tradeoffer_sent_at = now();

            $steamOrder->save();
            $order->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw $e;
        }
    }

    public function getItemsValue(string $steamid, array $items)
    {
        $inventory = SteamAccount::getInventory($steamid);
        $items = collect($items);

        // Filter the entire user inventory to only requested items
        $items = collect($inventory)->keyBy('assetid')->only($items->pluck('assetid'));

        $data = $this->mergePricingData($items);

        return $data->sum('price');
    }

    public function getInventory($steamid)
    {
        // Fetch user inventory
        $inventory = SteamAccount::getInventory($steamid);

        $inventory = $this->mergePricingData($inventory);

        return $inventory;
    }

    public function mergePricingData($inventory)
    {
        $inventory = collect($inventory);

        // Check if our database is empty
        if (SteamItem::count() === 0)
            throw new Exception('Empty SteamItem table, fill it...');

        // Pluck market_hash_names from user inventory
        $requestedItems = $inventory->pluck('market_hash_name');

        // Fetch pricing data from user inventory
        $prices = SteamItem::query()
                           ->whereIn('market_hash_name', $requestedItems)
                           ->where('price', '>', config('payment-system.minimum-steam-item-value', 30))
                           ->get()
                           ->keyBy('market_hash_name');

        // Filter negative priced items and that have no pricing data
        $inventory = $inventory->reject(function ($item) use ($prices) {
            // Some items are missing market_hash_name
            $itemName = $item['market_hash_name'] ?? null;

            return !$itemName || !$prices->get($itemName) || $prices[ $itemName ]->price < 0;
        });

        // Merge inventory and pricing data
        return $inventory->map(function ($item) use ($prices) {
            return $this->mergeItemData($item, $prices);
        });
    }

    private function mergeItemData($item, $prices)
    {
        $item = collect($item);
        $itemName = $item['market_hash_name'];
        $price = $prices[ $itemName ];

        $itemFields = ['market_hash_name', 'appid', 'contextid', 'assetid'];
        $dataFields = ['price', 'icon_url'];

        $itemData = $item->only($itemFields);
        $priceData = $price->only($dataFields);

        return $itemData->merge($priceData);
    }
}
