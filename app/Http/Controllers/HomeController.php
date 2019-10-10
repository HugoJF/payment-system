<?php

namespace App\Http\Controllers;

use App\Forms\OrderForm;
use App\Order;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilder;

class HomeController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index()
	{
		return redirect()->route('admin.home');
	}

	public function home()
	{
		$orders = Order::query()->limit(5)->get();

		return view('admin.home', compact('orders'));
	}

	public function orders()
	{
		$orders = Order::query()->paginate(10);

		return view('admin.orders', compact('orders'));
	}
}
