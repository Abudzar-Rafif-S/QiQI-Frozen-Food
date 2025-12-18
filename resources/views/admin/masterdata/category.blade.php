@extends('admin.index')

@section('content')
<div class="container mt-4">

    {{-- Notifikasi sukses --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="shadow card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Manajemen Kategori</h4>
            <button class="btn btn-outline-primary" data-toggle="modal" data-target="#createCategoryModal">
                + Tambah Kategori
            </button>
        </div>

        <div class="card-body">

            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th width="50">No</th>
                        <th>Nama Kategori</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $index => $category)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $category->name }}</td>
                            <td>
                                <!-- Edit -->
                                <button class="btn btn-outline-warning btn-sm"
                                        data-toggle="modal"
                                        data-target="#editCategoryModal{{ $category->id }}">
                                    Edit
                                </button>

                                <!-- Delete -->
                                <button class="btn btn-outline-danger btn-sm"
                                        data-toggle="modal"
                                        data-target="#deleteCategoryModal{{ $category->id }}">
                                    Hapus
                                </button>
                            </td>
                        </tr>

                        {{-- Modal EDIT --}}
                        <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Kategori</h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Nama Kategori</label>
                                                <input type="text"
                                                       name="name"
                                                       value="{{ $category->name }}"
                                                       class="form-control"
                                                       required>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                Batal
                                            </button>
                                            <button type="submit" class="btn btn-warning">
                                                Update
                                            </button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>

                        {{-- Modal DELETE --}}
                        <div class="modal fade" id="deleteCategoryModal{{ $category->id }}" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST">
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
                                            <p>Hapus kategori <b>{{ $category->name }}</b>?</p>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                Batal
                                            </button>
                                            <button type="submit" class="btn btn-danger">
                                                Hapus
                                            </button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>

                    @endforeach
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $categories->links() }}
            </div>

        </div>
    </div>
</div>

{{-- Modal CREATE --}}
<div class="modal fade" id="createCategoryModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kategori Baru</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Kategori</label>
                        <input type="text"
                               name="name"
                               class="form-control"
                               placeholder="Masukkan nama kategori"
                               required>
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
