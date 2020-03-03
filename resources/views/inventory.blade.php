@php
    $avatar = false;
    $topError = true;
@endphp

@extends('layouts.app')

@section('content')
    <form method="POST" action="{{ route('orders.steam.execute', $order) }}">
        @csrf
        <div
            data-react="inventory"
            data-inventory='@json($items)'
            data-order='@json($order)'
            data-csrf="{{ csrf_token() }}"
        ></div>
    </form>
@endsection
