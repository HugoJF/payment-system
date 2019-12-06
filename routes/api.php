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

// TODO: add IPN controllers
Route::get('ipn/mercadopago', function (Request $request) {
	\Illuminate\Support\Facades\Log::info('Received IPN from MercadoPago', ['data', $request->all()]);

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
		Route::get('{order}', 'OrderApiController@show')->name('show');

		Route::post('/', 'OrderApiController@store')->name('store');
	});
});