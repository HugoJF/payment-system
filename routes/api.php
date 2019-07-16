<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
	return $request->user();
});

// TODO: add IPN controllers
Route::get('ipn/mercadopago', function () {
	return 'true';
})->name('mp-notifications');

Route::get('paypal/success', function () {
	return 'true';
})->name('pp-orders.success');

Route::get('paypal/cancel', function () {
	return 'true';
})->name('pp-orders.cancel');

Route::prefix('v1')->group(function () {
	Route::prefix('orders')->name('orders.')->group(function () {
		Route::get('{order}', 'OrderController@show')->name('show');

		Route::post('/', 'OrderController@store')->name('store');

		Route::post('{order}/mercadopago', 'MPOrderController@init')->name('mp-init');
		Route::patch('{order}/mercadopago/execute', 'MPOrderController@execute')->name('mp-execute');

		Route::post('{order}/paypal', 'PayPalOrderController@init')->name('paypal-init');
		Route::patch('{order}/paypal/execute', 'PayPalOrderController@execute')->name('paypal-execute');

		Route::post('{order}/steam', 'SteamOrderController@init')->name('steam-init');
		Route::patch('{order}/steam/execute', 'SteamOrderController@execute')->name('steam-execute');
	});

	Route::prefix('steam')->group(function () {
		Route::get('inventory/{steamid}', 'SteamOrderController@inventory')->name('inventory');
	});
});