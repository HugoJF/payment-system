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

Route::post('orders/{order}/steam/execute', 'SteamOrderController@execute')->name('orders.steam.execute');

Route::get('orders/{order}/{action?}', 'OrderController@show')->name('orders.show');

Auth::routes();

Route::middleware(['admin'])->prefix('admin')->group(function () {

	Route::get('/home', 'HomeController@home')->name('admin.home');
	Route::get('/', 'HomeController@index')->name('admin.index');

	Route::get('orders', 'HomeController@orders')->name('admin.orders');

	Route::get('orders/{order}/recheck', 'HomeController@recheck')->name('admin.orders.recheck');

	Route::get('orders/{order}', 'HomeController@show')->name('admin.orders.show');
	Route::get('orders/{order}/edit', 'HomeController@edit')->name('admin.orders.edit');

	Route::patch('orders/{order}', 'HomeController@update')->name('admin.orders.update');
});
