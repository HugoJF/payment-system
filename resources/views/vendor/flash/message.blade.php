@foreach (session('flash_notification', collect())->toArray() as $message)
{{--    <div class="alert--}}
{{--                    alert-{{ $message['level'] }}--}}
{{--    {{ $message['important'] ? 'alert-important' : '' }}"--}}
{{--         role="alert"--}}
{{--    >--}}
{{--    </div>--}}
    <div class="flex items-center w-full p-4 bg-red-dark">
        <span class="inline-flex flex-no-shrink items-center justify-center w-8 h-8 mr-2 bg-white font-bold text-red-dark rounded-full">!</span>
        <span class="flex-shrink text-red-lightest">{!! $message['message'] !!}</span>
    </div>
@endforeach

{{ session()->forget('flash_notification') }}
