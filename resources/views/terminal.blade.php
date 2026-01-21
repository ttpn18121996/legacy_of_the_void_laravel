<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Terminal</title>

    <link rel="stylesheet" href="{{ asset('static/css/terminal.css?v='.time()) }}">
</head>
<body class="terminal">
    <script src="{{ asset('static/js/terminal.js?v='.time()) }}"></script>
    <script>
        terminal.configure({
            username: "{{ str(config('app.name'))->snake() }}",
            is_logged_in: true,
            php_ver: "{{ phpversion() }}",
            laravel_ver: "{{ app()->version() }}",
            helper: @json($helper),
        }).init();
    </script>
</body>
</html>
