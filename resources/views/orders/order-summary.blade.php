@extends('layouts.app')

@php
    $color = 'blue';
    $state = 'AGUARDANDO';
@endphp

@section('content')
    <div class="flex flex-col p-4 justify-center items-center sm:p-6">
        @include('ui.order-state')

        <p class="mt-12 text-grey-dark text-sm">{{ $order->reason }}</p>
        <h2 class="mt-12 uppercase text-grey-dark text-center text-2xl font-normal tracking-wide">Valor total</h2>

        <p class="flex mt-8 pb-4 justify-center items-baseline text-center text-5xl">
            <span class="mr-1 text-3xl text-grey font-normal">R$</span>
            <span class="text-grey-darkest font-semibold">{{ round($order->preset_amount / 100, 2) }}</span>
        </p>

        @include('ui.button', ['payUrl' => $payUrl, 'title' => 'Pagar'])
    </div>
@endsection
