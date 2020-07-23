<?php

namespace App\Http\Controllers;

use App\Forms\OrderForm;
use App\Order;
use App\OrderService;
use App\Services\Forms\OrderForms;
use App\Services\OrderPreApprovalService;
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
        $orders = Order::query()->orderBy('created_at', 'DESC')->limit(15)->get();

        return view('admin.home', compact('orders'));
    }

    public function orders()
    {
        $orders = Order::query()->orderBy('created_at', 'DESC')->paginate(15);

        return view('admin.orders', compact('orders'));
    }

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

    public function preApprove(OrderPreApprovalService $preApprovalService, Order $order)
    {
        $preApprovalService->handle($order);

        flash()->success("Order pre-approved!");

        return back();
    }

    public function update(OrderService $service, Request $request, Order $order)
    {
        $service->update($order, $request->all());

        return redirect()->route('admin.orders.show', $order);
    }

    public function recheck(Order $order)
    {
        $order->recheck();

        return back();
    }
}
