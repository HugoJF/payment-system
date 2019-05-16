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

Route::get('reference', function () {
	return view('reference');
});

Route::get('orderss/{order}', 'OrderController@show');

Route::get('preference/{preference}', function ($preference) {
	dd(App\Classes\MP2::get('checkout', 'preferences/' . $preference));
});

Route::get('search/{id}', function ($id) {
	$response = App\Classes\MP2::get('v1', 'payments/search?external_reference=' . $id);

	$a = collect($response['response']['results']);

	$b  = $a->pluck('transaction_amount');
	$c = $b->sum();
	return round($c * 100, 2);
});

Route::get('create-order', function () {
	$order = \App\Order::make();

	$order->public_id = substr(md5(microtime(true)), 0, 5);

	$order->reason = 'VIPzão bonito do servidor do de_nerd';
	$order->return_url = 'https://denerdtv.com';
	$order->cancel_url = 'https://denerdtv.com/';
		$order->preset_amount = 465;

	$order->payer_steam_id = '76561198033283983';
	$order->payer_tradelink = 'https://steamcommunity.com/tradeoffer/new/?partner=73018255&token=iFVJ4YIQ';

	$order->unit_price = 35;
	$order->unit_price_limit = 20;
	$order->discount_per_unit = 0.26;
	$order->min_units = 15;
	$order->max_units = 90;

	$order->save();

	return redirect(url('/orders/' . $order->public_id));
});

Route::get('{all?}', function () {
	return view('home');
})->where('all', '([A-z\d\-\/_.]+)?');

Route::get('/', function () {
	return view('welcome');
});
