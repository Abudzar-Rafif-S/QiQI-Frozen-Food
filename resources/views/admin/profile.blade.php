<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">

        <h2 class="mb-4">Admin Profile</h2>
        <div class="mb-3">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>

        <!-- Update Admin Info Form -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Informasi Admin</div>
            <div class="card-body">
                @include('admin.partials.update-profile')
            </div>
        </div>

        <!-- Update Password Form -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">Ubah Password</div>
            <div class="card-body">
                @include('admin.partials.update-password')
            </div>
        </div>

        <!-- Delete Account Form -->
        <div class="card mb-4">
            <div class="card-header bg-danger text-white">Hapus Akun</div>
            <div class="card-body">
                @include('admin.partials.delete-acount')
            </div>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
</body>

</html>
