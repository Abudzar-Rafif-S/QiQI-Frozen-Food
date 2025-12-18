<div class="sidebar-profile">
    <div class="sidebar-menu">

        <a href="{{ route('customer.profile.index', ['section' => 'profile']) }}"
            class="sidebar-item {{ $active === 'profile' ? 'active' : '' }}">
            <i class="fa-solid fa-id-card mr-2"></i> Profil Saya
        </a>

        <a href="{{ route('customer.profile.index', ['section' => 'update-profile']) }}"
            class="sidebar-item {{ $active === 'update-profile' ? 'active' : '' }}">
            <i class="fa-solid fa-user-pen mr-2"></i> Update Profil
        </a>

        <a href="{{ route('customer.profile.index', ['section' => 'update-password']) }}"
            class="sidebar-item {{ $active === 'update-password' ? 'active' : '' }}">
            <i class="fa-solid fa-key mr-2"></i> Ubah Password
        </a>

        <a href="{{ route('customer.profile.index', ['section' => 'delete-account']) }}"
            class="sidebar-item sidebar-danger {{ $active === 'delete-account' ? 'active-danger' : '' }}">
            <i class="fa-solid fa-trash mr-2"></i> Hapus Akun
        </a>

    </div>
</div>
