<?php

namespace App\Http\Controllers;

use App\Forms\OrderForm;
use App\Order;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilder;

class HomeController extends Controller
{
	public function index()
	{
		return redirect()->route('admin.home');
	}

	public function home()
	{
		$orders = Order::query()->orderBy('created_at', 'DESC')->limit(5)->get();

		return view('admin.home', compact('orders'));
	}

	public function orders()
	{
		$orders = Order::query()->orderBy('created_at', 'DESC')->paginate(10);

		return view('admin.orders', compact('orders'));
	}

	public function show(Order $order)
	{
		return view('admin.order', compact('order'));
	}

	public function recheck(Order $order)
	{
		$order->recheck();

		$id = e($order->id);
		flash()->success("Rechecked order $id.");

		return back();
	}
}
