@extends('layouts.app')

@php
    $color = 'blue';
    $state = 'AGUARDANDO';
@endphp

@section('content')
    <div class="flex flex-col p-4 justify-center items-center sm:p-6">
        @include('ui.order-state')

        <h2 class="mt-16 mb-4 uppercase text-grey-darker text-center text-2xl font-normal tracking-wide">AGUARDANDO</h2>

        <p class="text-sm font-light text-grey tracking-normal">Por favor aceite a tradeoffer e aguarde a identificação do sistema!</p>

        <div data-react="pending-order" class="mt-1 flex flex-col items-center" data-id="{{ $order->id }}"></div>

        @include('ui.button', [
            'payUrl' => "https://steamcommunity.com/tradeoffer/$tradeofferId/",
            'title' => 'Tradeoffer',
            'target' => '_blank'
        ])
    </div>
@endsection
