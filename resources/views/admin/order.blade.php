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

                        <!-- Webhook URL -->
                        <tr>
                            <td>Webhook URL</td>
                            <td><code>{{ $order->webhook_url }}</code></td>
                        </tr>

                        <!-- Webhook attempts -->
                        <tr>
                            <td>Webhook attempts</td>
                            <td>
                                @if($order->webhook_attempts <= 1)
                                    <span class="badge badge-success">{{ $order->webhook_attempts }} attempts</span>
                                @else
                                    <span class="badge badge-warning">{{ $order->webhook_attempts }} attempts</span>
                                @endif
                            </td>
                        </tr>

                        <!-- Webhooked at -->
                        <tr>
                            <td>Webhooked at</td>
                            <td title="{{ $order->webhooked_at }}">
                                @if($order->webhooked_at)
                                    {{ $order->webhooked_at->diffForHumans() }}
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>

                        <!-- Webhook attempted at -->
                        <tr>
                            <td>Webhook attempted at</td>
                            <td title="{{ $order->webhook_attempted_at }}">
                                @if($order->webhook_attempted_at)
                                    {{ $order->webhook_attempted_at->diffForHumans() }}
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Specific order details -->
    <div class="mt-4 row justify-content-center">
        <div class="col-12">
            @includeWhen($order->type() === \App\PayPalOrder::class, 'admin.order.show-paypal-details')
            @includeWhen($order->type() === \App\MPOrder::class, 'admin.order.show-mercadopago-details')
        </div>
    </div>

    <!-- Webhook details -->
    <div class="mt-4 justify-content-center">
        <div class="col-12">
            @include('admin.cards.webhooks', ['webhooks' => $order->webhooks])
        </div>
    </div>
@endsection
