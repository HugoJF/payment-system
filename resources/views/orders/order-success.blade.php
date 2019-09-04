@php
        $color = 'green';
        $state = 'PAGO';
@endphp

@extends('layout.app')

@section('content')
    <div class="flex flex-col p-4 justify-center items-center sm:p-6">
        @include('ui.order-state')
        
        <h2 class="mt-16 uppercase text-grey-dark text-center text-2xl font-normal tracking-wide">Valor total</h2>
        <p class="flex mt-8 mb-4 justify-center items-baseline text-center text-5xl">
            <span class="mr-1 text-3xl text-grey font-normal">R$</span>
            <span class="text-grey-darkest font-semibold">{{ number_format($order->preset_amount / 100, 2) }}</span>
        </p>
        
        <div class="my-2 w-16 h-16 fill-current text-green">
            @include('icons.check')
        </div>
        
        <a href="{{ $order->return_url }}" class="py-4 px-12 text-grey-darker text-xl font-normal no-underline hover:text-grey-darkest hover:underline">‹ Voltar</a>
    </div>
@endsection