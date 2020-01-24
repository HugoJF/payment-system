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
                <th>Type</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($orders as $order)
                <tr>
                    <!-- ID -->
                    <td>
                        <code>{{ $order->id }}</code>
                    </td>

                    <!-- Reason -->
                    <td>
                        <p>{{ $order->reason }}</p>
                    </td>

                    <!-- Paid -->
                    <td>
                        <span class="badge badge-{{ $order->paid ? 'success' : 'danger' }}">
                            R$ {{ number_format($order->paid_amount / 100, 2) }}
                            @if($order->paidUnits($order))
                                ({{ $order->paidUnits($order) }})
                            @endif
                        </span>
                    </td>

                    <!-- Cost -->
                    <td>
                        <span class="badge badge-primary">
                            R$ {{ number_format($order->preset_amount / 100, 2)}}
                            @if($order->units($order))
                                ({{ $order->units($order) }})
                            @endif
                        </span>
                    </td>

                    <!-- Created at -->
                    <td title="{{ $order->created_at }}">
                        {{ $order->created_at->diffForHumans() }}
                    </td>

                    <td><span class="badge badge-dark">{{ class_basename($order->type()) }}</span></td>

                    <!-- Actions -->
                    <td>
                        <div class="btn-group">
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.orders.recheck', $order) }}">Recheck</a>
                            <a class="btn btn-sm btn-primary" href="{{ route('admin.orders.show', $order) }}">Info</a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.orders.edit', $order) }}">Edit</a>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
