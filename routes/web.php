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

    $order->webhook_url = 'https://webhook.site/8aff0bf3-1795-41c8-90dd-aedd24e747c6';

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

Auth::routes();

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

	Route::get('/home', 'HomeController@home')->name('admin.home');
	Route::get('/', 'HomeController@index')->name('admin.index');

	Route::get('orders', 'HomeController@orders')->name('admin.orders');

	Route::get('orders/{order}/recheck', 'HomeController@recheck')->name('admin.orders.recheck');

	Route::get('orders/{order}', 'HomeController@show')->name('admin.orders.show');
	Route::get('orders/{order}/edit', 'HomeController@edit')->name('admin.orders.edit');

	Route::patch('orders/{order}', 'HomeController@update')->name('admin.orders.update');
});
