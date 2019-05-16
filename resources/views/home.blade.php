<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Laravel</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    
    <!-- Styles -->
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
</head>
<body class="trans bg-grey-light font-sans">

<div id="root" class="flex flex-col items-stretch justify-center p-6 md:p-12 my-32">
</div>

<script src="{{ mix('/js/app.js') }}"></script>
</body>
</html>