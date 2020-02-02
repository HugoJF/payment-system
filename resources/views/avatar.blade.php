@php
    $avatarUrl = $order->avatar ?? null;
@endphp

<div class="relative flex justify-center w-full m-auto">
    @if($avatar ?? true)
        <div class="hidden absolute -translate-50 self-center pin-t p-3 justify-center items-center bg-white rounded-full shadow sm:flex">
            @if($avatarUrl ?? false)
                <img class="h-32 w-32 rounded-full" src="{{ $avatarUrl }}"/>
            @else
                <div class="h-32 w-32"></div>
            @endif
        </div>
    @endif
</div>
