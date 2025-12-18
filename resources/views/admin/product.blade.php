@extends('admin.index')

@section('content')
<div class="container mt-4">

    {{-- Notifikasi Berhasil --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Header --}}
    <div class="mb-4 d-flex justify-content-between">
        <h3>Manajemen Produk</h3>
        <button class="btn btn-outline-primary"
            data-toggle="modal"
            data-target="#addProductModal">
            + Tambah Produk
        </button>
    </div>

    {{-- LIST PRODUK DALAM BENTUK CARD --}}
    <div class="row">

        @foreach($products as $index => $p)
            <div class="mb-3 col-12">

                <div class="shadow-sm card">

                    <div class="card-body d-flex">

                        {{-- Gambar Produk --}}
                        <div class="mr-3">
                            <img src="{{ asset('products/' . $p->picture) }}"
                                 class="img-thumbnail"
                                 style="width: 120px; height:120px; object-fit: cover;">
                        </div>

                        {{-- Detail Produk --}}
                        <div class="flex-grow-1">

                            <h5 class="mb-1">{{ $p->product_name }}</h5>

                            <p class="mb-1 text-muted">
                                Kategori: <strong>{{ $p->category->name ?? '-' }}</strong> <br>
                                Brand: <strong>{{ $p->brand->name ?? '-' }}</strong>
                            </p>

                            <p class="mb-1">
                                Harga:
                                <strong>Rp {{ number_format($p->price) }}</strong>
                                | Stok:
                                <strong>{{ $p->stock }}</strong>
                            </p>

                        </div>

                        {{-- Aksi --}}
                        <div class="ml-3 text-right">

                            {{-- Tombol Edit --}}
                            <button class="mb-1 btn btn-outline-warning btn-sm"
                                data-toggle="modal"
                                data-target="#editProductModal{{ $p->id }}">
                                Edit
                            </button>

                            {{-- Tombol Edit Stok --}}
                            <button class="mb-1 btn btn-outline-info btn-sm"
                                data-toggle="modal"
                                data-target="#editStockModal{{ $p->id }}">
                                Stok
                            </button>

                            {{-- Tombol Hapus --}}
                            <form action="{{ route('admin.products.destroy', $p->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Hapus produk ini?')">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-outline-danger btn-sm">
                                    Hapus
                                </button>
                            </form>

                        </div>

                    </div>
                </div>
            </div>

            {{-- INCLUDE MODAL (SUDAH AUTO SESUAI BS4) --}}
            @include('admin.product-modals.edit', [
                'product' => $p,
                'categories' => $categories,
                'brand' => $brand
            ])

            @include('admin.product-modals.stock', [
                'product' => $p
            ])

        @endforeach

    </div>

    {{-- Pagination --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $products->links() }}
    </div>

</div>

{{-- Modal Tambah Produk --}}
@include('admin.product-modals.create', [
    'categories' => $categories,
    'brand' => $brand
])

@endsection
