<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">

        <a class="navbar-brand" href="{{ route('customer.dashboard') }}">
            <img src="{{ asset('component/Logo.png') }}" class="logo-navbar" alt="Logo">
        </a>

        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <!-- Right -->
            <ul class="ml-auto navbar-nav">

                <li class="mx-2 nav-item">
                    <a class="nav-link custom-nav {{ request()->is('customer/dashboard') ? 'active' : '' }}"
                        href="{{ route('customer.dashboard') }}">Dashboard</a>
                </li>

                <li class="mx-2 nav-item">
                    <a class="nav-link custom-nav {{ Request::routeIs('customer.products') ? 'active-link' : '' }}"
                        href="{{ route('customer.products') }}">Product</a>
                </li>

                <!-- Search Form -->
                <li class="mx-2 nav-item">
                    <form action="{{ route('customer.search') }}" method="GET"
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
                    <a class="nav-link {{ Request::routeIs('customer.wishlist.index') ? 'active-icon' : '' }}"
                        href="{{ route('customer.wishlist.index') }}"><i class="fas fa-heart icon"></i></a>
                </li>

                <li class="mx-2 nav-item position-relative">
                    <a class="nav-link {{ Request::routeIs('customer.cart.index') ? 'active-icon' : '' }}"
                        href="{{ route('customer.cart.index') }}">
                        <i class="fas fa-shopping-cart icon"></i>
                        @if ($cartCount > 0)
                            <span class="cart-badge">{{ $cartCount }}</span>
                        @endif
                    </a>
                </li>


                <!-- User Dropdown -->
                <li class="mx-2 nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-toggle="dropdown">
                        @php
                            $avatarPath = 'avatars/default-avatar.png';
                            if ($navbarCustomer?->avatar) {
                                $avatarPath = 'avatars/' . $navbarCustomer->avatar;
                            }
                        @endphp
                        <img src="{{ asset($avatarPath) }}" class="navbar-avatar"
                            onerror="this.src='{{ asset('avatars/cat.png') }}'" alt="Avatar">
                        <span class="ms-2">{{ $navbarCustomer?->fullname ?? 'Customer' }}</span>
                    </a>

                    <div class="shadow dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="{{ route('customer.profile.index') }}">
                            <i class="fas fa-user"></i> Profil
                        </a>
                        <a class="dropdown-item" href="{{ route('customer.orders.index') }}"><i class="fas fa-box"></i> Pesanan</a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item text-danger" type="submit">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </div>
                </li>

            </ul>
        </div>

    </div>
</nav>
