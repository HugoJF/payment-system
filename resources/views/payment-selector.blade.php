@extends('layouts.app')

@section('content')
    <div class="flex flex-wrap p-4 justify-center items-stretch sm:p-6 sm:pt-20">
        <p class="mb-2 p-4 w-full text-center text-2xl text-grey-darkest">Escolha seu método de pagamento:</p>
        @if($order->canInit(\App\Order::PAYPAL))
            <div class="w-full sm:w-1/2 p-4 text-4xl">
                <a href="{{ route('orders.paypal.init', $order) }}" class="trans h-32 flex py-3 px-10 sm:px-5 justify-center items-center text-grey-darkest cursor-pointer rounded-lg bg-grey-lightest no-underline active:shadow-md hover:shadow hover:bg-white">
                    <img src="https://logodownload.org/wp-content/uploads/2014/10/paypal-logo.png"/>
                </a>
            </div>
        @endif
        
        @if($order->canInit(\App\Order::MERCADOPAGO))
            <div class="w-full sm:w-1/2 p-4 text-4xl">
                <a href="{{ route('orders.mp.init', $order) }}" class="trans h-32 flex py-3 px-10 sm:px-5 justify-center items-center text-grey-darkest cursor-pointer rounded-lg bg-grey-lightest no-underline active:shadow-md hover:shadow hover:bg-white">
                    <img src="http://www.freelogovectors.net/wp-content/uploads/2019/02/Mercadopago-logo.png"/>
                </a>
            </div>
        @endif
        
        @if(false)
            <div class="w-full sm:w-1/2 p-4 text-4xl">
                <a class="opacity-25 trans h-32 flex py-3 px-10 sm:px-5 justify-center items-center text-grey-darkest rounded-lg bg-grey-lightest cursor-not-allowed no-underline">
                    <img src="https://cdn-images-1.medium.com/max/1200/1*NarjT54CL02HHKsSiw68zQ.png"/>
                </a>
            </div>
        @endif
        
        @if($order->canInit(\App\Order::STEAM))
            <div class="w-full sm:w-1/2 p-4 text-4xl">
                <a href="{{ route('orders.steam.init', $order) }}" class="trans h-32 flex py-3 px-10 sm:px-5 justify-center items-center text-grey-darkest cursor-pointer rounded-lg bg-grey-lightest no-underline active:shadow-md hover:shadow hover:bg-white">
                    <img src="https://logodownload.org/wp-content/uploads/2014/09/counter-strike-global-offensive-cs-go-logo.png"/>
                </a>
            </div>
        @endif
    
    </div>
@endsection