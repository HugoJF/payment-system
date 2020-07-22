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
}
