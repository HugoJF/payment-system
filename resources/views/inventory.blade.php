@extends('layouts.app')

@section('content')
    <form method="POST" action="{{ route('orders.steam.execute', $order) }}">
        @csrf
        <div id="inventory"></div>
    </form>
    <script>
        let inventory = @json($items);
        let order = @json($order);
        let csrf = '{{ csrf_token() }}';
    </script>
@endsection