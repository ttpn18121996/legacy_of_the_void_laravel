<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('static/css/global.css?v='.time()) }}">
    <link rel="stylesheet" href="{{ asset('static/css/auth.css?v='.time()) }}">
</head>
<body>
    <div class="container">
        <form class="login-form" action="{{ route('login') }}" method="POST">
            @csrf
            <h1>Login</h1>

            <div class="form-input">
                <div class="form-input__group">
                    <input id="email" type="text" name="email" placeholder="Username" autocomplete="off" autofocus />
                </div>
                @error('email')
                    <label for="email" class="text-error">{{ $message }}</label>
                @enderror
            </div>

            <div class="form-input">
                <div class="form-input__group">
                    <input id="password" type="password" name="password" placeholder="Password" autocomplete="off" />
                </div>
                @error('password')
                    <label for="password" class="text-error">{{ $message }}</label>
                @enderror
            </div>

            <div class="form-button">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </form>
    </div>
    <script src="{{ asset('static/js/app.js?v='.time()) }}"></script>
    <script>
        lotv.init();
    </script>
</body>
</html>