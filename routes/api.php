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

Route::get('ipn/mercadopago', 'MPOrderController@ipn')->name('mp-notifications');

Route::prefix('v1')->group(function () {
	Route::prefix('orders')->name('orders.')->group(function () {
		Route::get('{order}', 'OrderApiController@show')->name('show');

		Route::post('/', 'OrderApiController@store')->name('store');
	});
});
