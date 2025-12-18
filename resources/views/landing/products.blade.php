@extends('landing.index')

@section('content')
    <div class="container py-5">

        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white shadow-sm px-3 py-2">
                <li class="breadcrumb-item"><a href="{{ route('guest.home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Katalog Produk</li>
            </ol>
        </nav>

        {{-- Title --}}
        <div class="text-center mb-4">
            <h2 class="font-weight-bold">Katalog Produk</h2>
            <p class="text-muted">Temukan berbagai produk terbaik untuk kebutuhanmu</p>
        </div>

        {{-- Product Grid --}}
        <div class="row">
            @forelse($products as $product)
                <div class="col-6 col-md-4 col-lg-3 mb-4">
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
                    <h5 class="text-muted">Belum ada produk tersedia.</h5>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-4 d-flex justify-content-center">
            {{-- Bootstrap 4 Pagination --}}
            {{ $products->links() }}
        </div>

    </div>
@endsection
