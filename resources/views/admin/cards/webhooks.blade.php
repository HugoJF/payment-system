<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>Webhooks</div>
        <div></div> <!-- Empty to avoid centering -->
    </div>

    <div class="card-body">
        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Status</th>
                <th>Response</th>
                <th>Content type</th>
                <th>Error</th>
                <th>Created at</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($webhooks as $webhook)
                <tr>
                    <!-- ID -->
                    <td>
                        <code>{{ $webhook->id }}</code>
                    </td>

                    <!-- Status -->
                    <td>
                        <span class="badge badge-primary">{{ $webhook->status }}</span>
                    </td>

                    <!-- Response -->
                    <td>
                        <code>{{ $webhook->response }}</code>
                    </td>

                    <!-- Content type -->
                    <td>
                        <code>{{ $webhook->content_type }}</code>
                    </td>

                    <!-- Error -->
                    <td>
                        {{ $webhook->error }}
                    </td>

                    <!-- Created at -->
                    <td>
                        <span title="{{ $webhook->created_at }}">
                            {{ $webhook->created_at->diffForHumans() }}
                        </span>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
