<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
	public function store(Request $request)
	{
		$validation = Validator::make($request->all(), [
			'reason'                => 'required',
			'return_url'            => 'required',
			'cancel_url'            => 'required',
			'preset_amount'         => 'required|numeric',
			'unit_price'            => 'required|numeric',
			'unit_price_limit'      => 'required|numeric',
			'discount_per_unit'     => 'required|numeric',
			'min_units'             => 'required|numeric',
			'max_units'             => 'required|numeric',
			'payer_steam_id'        => 'string',
			'avatar'                => 'string',
			'payer_tradelink'       => 'string|nullable',
			'product_name_singular' => 'string|nullable',
			'product_name_plural'   => 'string|nullable',
		]);

		// Check if request has every required field
		if ($validation->fails())
			return response($validation->errors()->toArray(), 400);

		// Prepare database row
		$order = Order::make();

		$order->fill($validation->validated());

		$order->save();

		// Return details
		return $order;
	}

	public function show(Order $order)
	{
		return $order;
	}
}
