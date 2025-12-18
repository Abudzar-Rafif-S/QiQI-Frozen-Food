<nav class="shadow-sm navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">

        {{-- LOGO --}}
        <a class="navbar-brand" href="{{ route('guest.home') }}">
            <img src="{{ asset('component/Logo.png') }}" class="logo-navbar" alt="Logo">
        </a>


        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- MENU --}}
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="ml-auto navbar-nav">

                <li class="mx-2 nav-item">
                    <a class="nav-link custom-nav {{ Request::routeIs('guest.products') ? 'active-link' : '' }}"
                        href="{{ route('guest.products') }}">
                        Produk
                    </a>
                </li>

                <!-- Search Form -->
                <li class="mx-2 nav-item">
                    <form action="{{ route('guest.search') }}" method="GET"
                        class="my-2 form-inline my-lg-0 ml-lg-3">
                        <div class="input-group search-group">
                            <input class="form-control search-input" type="search" name="q"
                                placeholder="Cari produk, brand, kategori..." value="{{ request('q') }}">

                            <div class="input-group-append">
                                <button class="btn search-btn" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                </li>

                <li class="mx-2 nav-item">
                    <a class="nav-link custom-nav {{ Request::routeIs('guest.about') ? 'active-link' : '' }}"
                        href="{{ route('guest.about') }}">
                        Tentang Kami
                    </a>
                </li>

                <li class="mx-2 nav-item">
                    <a class="nav-link custom-nav {{ Request::routeIs('login') ? 'active-link' : '' }}"
                        href="{{ route('login') }}">
                        Login
                    </a>
                </li>

                <li class="mx-2 nav-item">
                    <a class="nav-link custom-nav {{ Request::routeIs('register') ? 'active-link' : '' }}"
                        href="{{ route('register') }}">
                        Register
                    </a>
                </li>

            </ul>
        </div>

    </div>
</nav>
