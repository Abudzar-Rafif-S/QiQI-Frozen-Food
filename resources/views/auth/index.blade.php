<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Authentication')</title>

    {{-- CDN --}}
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900&display=swap" rel="stylesheet">

    {{-- CSS lokal --}}
    <link rel="stylesheet" href="{{ secure_asset('auth/css/style.css') }}">
</head>

<body>
    <section class="ftco-section">
        @yield('content')
    </section>

    {{-- JS lokal --}}
    <script src="{{ secure_asset('auth/js/jquery.min.js') }}"></script>
    <script src="{{ secure_asset('auth/js/popper.js') }}"></script>
    <script src="{{ secure_asset('auth/js/bootstrap.min.js') }}"></script>
    <script src="{{ secure_asset('auth/js/main.js') }}"></script>
</body>

</html>
