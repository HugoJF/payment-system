<div class="card">
    <div class="card-header">PayPal details</div>
    
    <div class="card-body">
        <table class="table">
            <tbody>
            <!-- Token -->
            <tr>
                <td>Token</td>
                <td>
                    <code>{{ $order->orderable->token }}</code>
                </td>
            </tr>
            
            <!-- Init URL -->
            <tr>
                <td>Init URL</td>
                <td>
                    @if($order->orderable->link)
                        <a href="{{ $order->orderable->link }}">
                            {{ parse_url($order->orderable->link)['scheme'] }}://{{ parse_url($order->orderable->link)['host'] }}
                        </a>
                    @else
                        <span class="badge badge-danger">N/A</span>
                    @endif
                </td>
            </tr>
            
            <!-- Transaction ID -->
            <tr>
                <td>Transaction ID</td>
                <td><code>{{ $order->orderable->transaction_id }}</code></td>
            </tr>
            
            <!-- Status -->
            <tr>
                <td>Status</td>
                <td><span class="badge badge-primary">{{ $order->orderable->status }}</span></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>