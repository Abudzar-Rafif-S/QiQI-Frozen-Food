@extends('landing.index')

@section('content')
    <div class="container py-5">

        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white shadow-sm px-3 py-2">
                <li class="breadcrumb-item"><a href="{{ route('guest.home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    {{ $category->name }}
                </li>
            </ol>
        </nav>

        {{-- Title Section --}}
        <div class="mb-4 text-center">
            <h2 class="font-weight-bold">{{ $category->name }}</h2>
            <p class="text-muted">Menampilkan {{ $products->count() }} produk dalam kategori ini</p>
        </div>

        {{-- Product Grid --}}
        <div class="row">

            @forelse($products as $product)
                <div class="mb-4 col-md-3">
                    <div class="product-card">
                        <div class="product-image">
                            <img src="{{ asset('products/' . $product->picture) }}" alt="{{ $product->product_name }}" />
                        </div>

                        <div class="product-body">
                            <h5 class="text-center product-title">{{ $product->product_name }}</h5>

                            <div class="text-center product-price">
                                @if ($product->discount)
                                    @php
                                        $diskon = $product->discount->value;
                                        $hargaDiskon = $product->price - ($product->price * $diskon) / 100;
                                    @endphp

                                    <div class="text-center product-price">
                                        <span class="old-price">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </span>

                                        <span class="new-price">
                                            Rp {{ number_format($hargaDiskon, 0, ',', '.') }}
                                        </span>
                                    </div>
                                @else
                                    <div class="text-center product-price">
                                        <span class="new-price">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                @endif

                            </div>

                            <div class="gap-3 mt-3 product-actions d-flex justify-content-center">
                                <!-- Add to cart -->
                                <a href="{{ route('login') }}" class="action-icon">
                                    <i class="mx-1 fas fa-shopping-cart"></i>
                                </a>

                                <!-- Wishlist -->
                                <a href="{{ route('login') }}" class="action-icon">
                                    <i class="mx-1 fas fa-heart"></i>
                                </a>

                                <!-- View product -->
                                <a href="{{ route('guest.products.detail', $product->id) }}" class="mx-1 action-icon">
                                    <i class="mx-1 fas fa-eye"></i>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <h5 class="text-muted">Tidak ada produk di kategori ini.</h5>
                </div>
            @endforelse

        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $products->links() }}
        </div>

    </div>
@endsection
