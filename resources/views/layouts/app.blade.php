<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('static/css/global.css?v='.time()) }}">
    <link rel="stylesheet" href="{{ asset('static/css/app.css?v='.time()) }}">
    @stack('styles')
</head>
<body>
    <x-header />

    <div class="wrapper">
        <x-sidebar />

        <main>
            @yield('content')
        </main>

        @include('layouts.footer')
    </div>

    @yield('other')

    <div class="loading"></div>

    <script src="{{ asset('static/js/app.js?v='.time()) }}"></script>
    <script src="{{ asset('static/js/global-search.js?v='.time()) }}"></script>
    <script>
        lotv.init();
        lotv.useScrollToTop();
        lotv.useFilterGlobal();
        globalSearch.init();
    </script>
    @stack('scripts')
</body>
</html>
