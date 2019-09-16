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

Route::get('orders/{order}/init/{type}', 'OrderController@init')->name('orders.paypal.init');
Route::get('orders/{order}/init/paypal', 'OrderController@init')->name('orders.paypal.init');
Route::get('orders/{order}/init/mp', 'OrderController@init')->name('orders.mp.init');
Route::get('orders/{order}/init/steam', 'OrderController@init')->name('orders.steam.init');

Route::post('orders/{order}/steam/execute', 'SteamOrderController@execute')->name('orders.steam.execute');

Route::get('orders/{order}/{action?}', 'OrderController@show')->name('orders.show');
