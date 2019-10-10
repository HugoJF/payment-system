<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderService;
use App\Services\Forms\OrderForms;
use Illuminate\Http\Request;

class AdminController extends Controller
{

	public function show(Order $order)
	{
		return view('admin.order', compact('order'));
	}

	public function edit(OrderForms $forms, Order $order)
	{
		$form = $forms->edit($order);

		return view('form', [
			'title'       => 'Editing order',
			'form'        => $form,
			'submit_text' => 'Update',
		]);
	}

	public function update(OrderService $service, Request $request, Order $order)
	{
		$service->update($order, $request->all());

		return redirect()->route('admin.orders.show', $order);
	}
}
