<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'QIQI Frozen Food') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- CSS Lokal -->
    <link rel="stylesheet" href="{{ secure_asset('bootstrap/css/bootstrap.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ secure_asset('component/css/main.css') }}">
</head>

<body class="d-flex flex-column min-vh-100">
    {{-- NAVBAR --}}
    @include('customer.partials.navbar')

    {{-- MAIN CONTENT --}}
    <main class="flex-fill">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    @include('customer.partials.footer')

    {{-- JS --}}
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="{{ secure_asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>

</html>
