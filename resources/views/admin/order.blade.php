@extends('layouts.admin')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">Pedido <strong>#{{ $order->id }}</strong></div>

                <div class="card-body">
                    <table class="table">
                        <tbody>
                        <!-- ID -->
                        <tr>
                            <td>ID</td>
                            <td>
                                <code>{{ $order->id }}</code>
                            </td>
                        </tr>

                        <!-- Reason -->
                        <tr>
                            <td>Reason</td>
                            <td>
                                <p>{{ $order->reason }}</p>
                            </td>
                        </tr>

                        <!-- Paid -->
                        <tr>
                            <td>Paid</td>
                            <td>
                                        <span class="badge badge-primary">
                                            R$ {{ number_format($order->paid_amount / 100, 2) }}
                                            @if($order->units($order))
                                                ({{ $order->units($order) }})
                                            @endif
                                        </span>
                            </td>
                        </tr>

                        <!-- Cost -->
                        <tr>
                            <td>Cost</td>
                            <td>
                                        <span class="badge badge-primary">
                                            R$ {{ number_format($order->preset_amount / 100, 2)}}
                                            @if($order->paidUnits($order))
                                                ({{ $order->paidUnits($order) }})
                                            @endif
                                        </span>
                            </td>
                        </tr>

                        <!-- Created at -->
                        <tr>
                            <td>Created at</td>
                            <td title="{{ $order->created_at }}">{{ $order->created_at->diffForHumans() }}</td>
                        </tr>

                        <!-- Updated at -->
                        <tr>
                            <td>Updated at</td>
                            <td title="{{ $order->updated_at }}">{{ $order->updated_at->diffForHumans() }}</td>
                        </tr>

                        <!-- Type -->
                        <tr>
                            <td>Type</td>
                            <td><span class="badge badge-dark">{{ class_basename($order->type()) }}</span></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <br/>
    <div class="row justify-content-center">
        <div class="col-12">
            @includeWhen($order->type() === \App\PayPalOrder::class, 'admin.order.show-paypal-details')
            @includeWhen($order->type() === \App\MPOrder::class, 'admin.order.show-mercadopago-details')

        </div>
    </div>
@endsection
