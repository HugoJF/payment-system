<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>Pedidos</div>
        <div>
            <a class="btn btn-primary btn-sm" href="{{ route('admin.orders') }}">View all</a>
        </div>
    </div>

    <div class="card-body">
        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Reason</th>
                <th>Paid (units)</th>
                <th>Cost (units)</th>
                <th>Created at</th>
                <th>Webhooked at</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($orders as $order)
                <tr>
                    <!-- ID -->
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}">
                            <code>{{ $order->id }}</code>
                        </a>
                    </td>

                    <!-- Reason -->
                    <td>
                        <p>{{ $order->reason }}</p>
                    </td>

                    <!-- Paid -->
                    <td>
                        <span class="badge {{ $order->paid ? 'badge-success' : 'badge-danger' }}">
                            R$ {{ number_format($order->paid_amount / 100, 2) }}
                            @if($order->canComputeUnits())
                                ({{ $order->paid_units }})
                            @endif
                        </span>
                    </td>

                    <!-- Cost -->
                    <td>
                        <span class="badge badge-primary">
                            R$ {{ number_format($order->preset_amount / 100, 2)}}
                            @if($order->canComputeUnits())
                                ({{ $order->units }})
                            @endif
                        </span>
                    </td>

                    <!-- Created at -->
                    <td title="{{ $order->created_at }}">
                        {{ $order->created_at->diffForHumans() }}
                    </td>

                    <!-- Webhooked at -->
                    @php
                        $attemptClass = $order->webhook_attempts === 1 ? 'badge-success' : 'badge-warning';
                    @endphp
                    <td title="{{ $order->webhooked_at }}">
                        @if($order->webhooked_at)
                            <span class="badge {{ $attemptClass }}" title="{{ $order->webhook_attempts }} attempts">
                                {{ $order->webhooked_at->diffForHumans() }}
                            </span>
                        @elseif($order->webhook_url)
                            <span class="badge badge-danger">
                                {{ $order->webhook_attempts }} attempts
                            </span>
                        @else
                            <span class="badge badge-dark">
                                N/A
                            </span>
                        @endif
                    </td>

                    <!-- Type -->
                    <td>
                        <span class="badge badge-dark">{{ class_basename($order->type()) }}</span>
                    </td>

                    <!-- Actions -->
                    <td class="d-flex justify-content-end">
                        <div class="btn-group">
                            @if(!$order->pre_approved_at && !$order->paid)
                                {{ Form::open(['method' => 'PATCH', 'url' => route('admin.orders.pre-approve', $order)]) }}
                                <button class="btn btn-primary btn-sm" type="submit">Pre-approve</button>
                                {{ Form::close() }}
                            @endif
                            @if($order->view_url)
                                <a class="btn btn-sm btn-outline-primary" href="{{ $order->view_url }}">View</a>
                            @endif
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.orders.recheck', $order) }}">Recheck</a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.orders.edit', $order) }}">Edit</a>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
