<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Qiqi Frozen') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link href="{{ secure_asset('sbadmin2/libs/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ secure_asset('sbadmin2/css/sb-admin-2.css') }}" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        @include('admin.partials.sidebar')

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                @include('admin.partials.topbar')

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    @yield('content')

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="bg-white sticky-footer">
                <div class="container my-auto">
                    <div class="my-auto text-center copyright">
                        <span>Copyright &copy; Kelompok 1 ADPL Unesa</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="rounded scroll-to-top" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ secure_asset('sbadmin2/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ secure_asset('sbadmin2/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ secure_asset('sbadmin2/libs/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ secure_asset('sbadmin2/js/sb-admin-2.min.js') }}"></script>

    <!-- Page level plugins -->
    <!-- Page level plugins -->
    <script src="{{ secure_asset('sbadmin2/libs/chart.js/Chart.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ secure_asset('sbadmin2/js/demo/chart-area-demo.js') }}"></script>
    {{-- <script src="{{ secure_asset('sbadmin2/js/demo/chart-pie-demo.js') }}"></script> --}}

    @stack('scripts')
</body>

</html>
