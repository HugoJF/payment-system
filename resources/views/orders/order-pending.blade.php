@extends('layouts.app')

@php
    $color = 'blue';
    $state = 'AGUARDANDO';
@endphp

@section('content')
    <div class="flex flex-col p-4 justify-center items-center sm:p-6">
        @include('ui.order-state')
        
        <h2 class="mt-16 uppercase text-grey-darker text-center text-2xl font-normal tracking-wide">AGUARDANDO</h2>
        
        @if($tradeofferId)
            <div id="pending-tradeoffer" class="mt-1 flex flex-col items-center" data-tradeoffer-id="{{ $tradeofferId }}"></div>
        @else
            <div class="spinner-border w-16 h-16 mt-8 text-grey"></div>
        @endif
    </div>
@endsection