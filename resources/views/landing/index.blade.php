<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page | E-Commerce</title>

    {{-- Bootstrap CSS --}}
    <link rel="stylesheet" href="{{ secure_asset('bootstrap/css/bootstrap.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ secure_asset('component/css/main.css') }}">
</head>
<body>

    {{-- Navbar --}}
    @include('landing.partials.navbar')

    {{-- Content --}}
    <div>
        @yield('content')
    </div>

    {{-- Footer --}}
    @include('landing.partials.footer')

    {{-- Scripts --}}
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="{{ secure_asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>

</body>
</html>
