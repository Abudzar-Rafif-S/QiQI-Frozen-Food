@extends('admin.index')

@section('content')
<div class="container mt-4">

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Manajemen Brand</h4>

            <!-- Button trigger modal -->
            <button class="btn btn-outline-primary"
                data-toggle="modal"
                data-target="#createBrandModal">
                + Tambah Brand
            </button>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th width="50">NO</th>
                        <th>Nama Brand</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($brands as $index => $brand)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $brand->name }}</td>
                            <td>

                                <!-- Tombol Edit -->
                                <button class="btn btn-sm btn-outline-warning"
                                    data-toggle="modal"
                                    data-target="#editBrandModal{{ $brand->id }}">
                                    Edit
                                </button>

                                <!-- Tombol Hapus -->
                                <button class="btn btn-sm btn-outline-danger"
                                    data-toggle="modal"
                                    data-target="#deleteBrandModal{{ $brand->id }}">
                                    Hapus
                                </button>
                            </td>
                        </tr>

                        {{-- ================= MODAL EDIT ================= --}}
                        <div class="modal fade" id="editBrandModal{{ $brand->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('admin.brands.update', $brand->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Brand</h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Nama Brand</label>
                                                <input type="text" name="name" class="form-control"
                                                    value="{{ $brand->name }}" required>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                Batal
                                            </button>
                                            <button type="submit" class="btn btn-warning">Update</button>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- ================= MODAL DELETE ================= --}}
                        <div class="modal fade" id="deleteBrandModal{{ $brand->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <h5 class="modal-title">Konfirmasi Hapus</h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <p>Apakah kamu yakin ingin menghapus brand <b>{{ $brand->name }}</b>?</p>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                Batal
                                            </button>
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>

                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>

{{-- ================= MODAL CREATE ================= --}}
<div class="modal fade" id="createBrandModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.brands.store') }}" method="POST">
            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Brand Baru</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Brand</label>
                        <input type="text" name="name" class="form-control"
                            placeholder="Masukkan nama brand" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Tambah</button>
                </div>

            </div>

        </form>
    </div>
</div>

@endsection
