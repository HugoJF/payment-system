<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('create-order', function () {
	$order = \App\Order::make();

	$order->id = substr(md5(microtime(true)), 0, 5);

	$order->reason = 'VIPzão bonito do servidor do de_nerd';
	$order->return_url = 'https://denerdtv.com';
	$order->cancel_url = 'https://denerdtv.com/';
	$order->preset_amount = 465;

	$order->avatar = 'https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/45/45be9bd313395f74762c1a5118aee58eb99b4688_full.jpg';

	$order->payer_steam_id = '76561198033283983';
	$order->payer_tradelink = 'https://steamcommunity.com/tradeoffer/new/?partner=73018255&token=iFVJ4YIQ';

	$order->unit_price = 10;
	$order->unit_price_limit = 5;
	$order->discount_per_unit = 0.25;
	$order->min_units = 1;
	$order->max_units = 900;

	$order->save();

	return redirect()->route('orders.show', $order);
	//	dd($order->toArray());
});

Route::get('orders/{order}/init/{type}', 'OrderController@init')->name('orders.paypal.init');
Route::get('orders/{order}/init/paypal', 'OrderController@init')->name('orders.paypal.init');
Route::get('orders/{order}/init/mp', 'OrderController@init')->name('orders.mp.init');
Route::get('orders/{order}/init/steam', 'OrderController@init')->name('orders.steam.init');

Route::post('orders/{order}/steam/execute', 'SteamOrderController@execute')->name('orders.steam.execute');

Route::get('orders/{order}/{action?}', 'OrderController@show')->name('orders.show');
