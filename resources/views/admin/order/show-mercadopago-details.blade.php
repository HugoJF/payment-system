<div class="card">
    <div class="card-header">MercadoPago details</div>

    <div class="card-body">
        <table class="table">
            <tbody>
            <!-- Preference ID -->
            <tr>
                <td>Preference ID</td>
                <td>
                    <code>{{ $order->orderable->preference_id}}</code>
                </td>
            </tr>

            <!-- Paid amount-->
            <tr>
                <td>Paid Amount</td>
                <td>
                    <span class="badge badge-primary">R$ {{ number_format($order->orderable->paid_amount, 2) }}</span>
                </td>
            </tr>

            <!-- Amount -->
            <tr>
                <td>Amount</td>
                <td>
                    <span class="badge badge-primary">R$ {{ number_format($order->orderable->amount, 2) }}</span>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
