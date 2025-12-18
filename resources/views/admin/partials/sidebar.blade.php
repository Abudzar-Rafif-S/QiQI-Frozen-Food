<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-fw fa-store"></i>
        </div>
        <div class="mx-3 sidebar-brand-text">Qiqi Frozen Food</div>
    </a>

    <!-- Divider -->
    <hr class="my-0 sidebar-divider">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Transaksi -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#masterData"
            aria-expanded="true" aria-controls="masterData">
            <i class="fas fa-database"></i>
            <span>Master Data</span>
        </a>
        <div id="masterData" class="collapse">
            <div class="py-2 bg-white rounded collapse-inner">
                <a class="collapse-item" href="{{ route('admin.categories.index') }}">Category</a>
                <a class="collapse-item" href="{{ route('admin.brands.index') }}">Brand</a>
                <a class="collapse-item" href="{{ route('admin.shipping-rates.index') }}">Shipping Rates</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Product -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('admin.products.index') }}">
            <i class="fab fa-fw fa-product-hunt"></i>
            <span>Product</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Product -->
    <!-- Nav Item - Transaksi -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('admin.shippings.index') }}">
            <i class="fas fa-fw fa-file-invoice"></i>
            <span>Order List</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="border-0 rounded-circle" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
