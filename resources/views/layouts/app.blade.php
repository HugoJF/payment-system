<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Pagamentos | de_nerdTV</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
</head>
<body class="trans bg-grey-light font-sans">

@php
    $width = $width ?? 'w-1/3';
    $color = $color ?? 'grey';
@endphp

<div class="flex flex-col items-stretch justify-center p-6 md:p-12 sm:my-32">
    @include('avatar')
    <div class="flex flex-col m-auto lg:w-1/2 xl:{{ $width }} w-full justify-center bg-grey-lightest border-0 border-{{ $color }}-dark rounded-lg shadow-lg overflow-hidden">
        @yield('content')
        @include('flash::message')

        <div class="h-4 w-full">
            <div class="trans h-full w-full bg-{{ $color }}-dark"></div>
        </div>
    </div>
</div>
<script src="{{ mix('/js/app.js') }}"></script>
</body>
</html>
