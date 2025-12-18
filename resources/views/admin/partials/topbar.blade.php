<nav class="mb-4 bg-white shadow navbar navbar-expand navbar-light topbar static-top">

    <!-- Tombol Sidebar (hanya muncul ketika layar kecil) -->
    <button id="sidebarToggleTop" class="mr-3 btn btn-link d-md-none rounded-circle">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Navbar -->
    <ul class="ml-auto navbar-nav">

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- USER DROPDOWN -->
        <li class="nav-item dropdown no-arrow">
            @php
                $user = Auth::user();

                $avatarFile = 'avatars/cat.png'; // default avatar
                $displayName = $user->email; // fallback

                if ($user->isAdmin() && $user->admin) {
                    $displayName = $user->admin->fullname ?? $user->email;
                    if ($user->admin->avatar && file_exists(public_path('avatars/' . $user->admin->avatar))) {
                        $avatarFile = 'avatars/' . $user->admin->avatar;
                    }
                } elseif ($user->isCustomer() && $user->customer) {
                    $displayName = $user->customer->fullname ?? $user->email;
                    if ($user->customer->avatar && file_exists(public_path('avatars/' . $user->customer->avatar))) {
                        $avatarFile = 'avatars/' . $user->customer->avatar;
                    }
                }
            @endphp

            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 text-gray-600 d-none d-lg-inline small">{{ $displayName }}</span>
                <img class="img-profile rounded-circle" src="{{ asset($avatarFile) }}">
            </a>

            <div class="shadow dropdown-menu dropdown-menu-right animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{ route('admin.profile.edit') }}">
                    <i class="mr-2 text-gray-400 fas fa-user fa-sm fa-fw"></i>
                    Profil
                </a>

                <div class="dropdown-divider"></div>

                <!-- Tombol Logout -->
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="mr-2 text-gray-400 fas fa-sign-out-alt fa-sm fa-fw"></i>
                    Logout
                </a>
            </div>
        </li>


    </ul>
</nav>

<!--  LOGOUT MODAL (SB Admin 2) -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                Select "Logout" below if you are ready to end your session.
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>

                <!-- Tombol yang submit form POST ke route logout -->
                <a class="btn btn-primary" href="#"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                </a>
            </div>
        </div>
    </div>
</div>

<!-- HIDDEN FORM UNTUK LOGOUT LARAVEL -->
<form id="logout-form" method="POST" action="{{ route('logout') }}" class="d-none">
    @csrf
</form>
