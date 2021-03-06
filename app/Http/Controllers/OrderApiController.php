<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Order;
use App\OrderService;
use Illuminate\Http\Request;

class OrderApiController extends Controller
{
    public function store(OrderService $service, CreateOrderRequest $request)
    {
        return $service->make($request->validated());
    }

    public function show(Order $order)
    {
        return $order;
    }
}
