<div class="flex flex-col self-stretch items-center justify-between sm:flex-row sm:items-start">
    <h2 class="text-grey-dark text-lg font-mono font-medium"><strong>#</strong>{{ $order->id }}</h2>
    @include('ui.badge', ['color' => $color, 'content' => $state])
</div>