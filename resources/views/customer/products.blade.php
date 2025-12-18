@extends('customer.index')

@section('content')
    <div class="container py-5">

        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb">
            <ol class="px-3 py-2 bg-white shadow-sm breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Katalog Produk</li>
            </ol>
        </nav>

        {{-- Title --}}
        <div class="mb-4 text-center">
            <h2 class="font-weight-bold">Katalog Produk</h2>
            <p class="text-muted">Temukan berbagai produk terbaik untuk kebutuhanmu</p>
        </div>

        {{-- Product Grid --}}
        <div class="row">
            @forelse($products as $product)
                <div class="mb-4 col-md-3">
                    <div class="product-card">
                        <div class="product-image">
                            <img src="{{ asset('products/' . $product->picture) }}"
                                alt="{{ $product->product_name }}" />
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
                                <form action="{{ route('customer.cart.store') }}" method="POST" class="p-0 m-0 mx-1">
                                    @csrf

                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">

                                    <button type="submit" class="action-icon">
                                        <i class="mx-1 fas fa-shopping-cart"></i>
                                    </button>
                                </form>

                                <!-- Wishlist -->
                                <form action="{{ route('customer.wishlist.store') }}" method="POST" class="p-0 m-0 mx-1">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="action-icon">
                                        <i class="mx-1 fas fa-heart"></i>
                                    </button>
                                </form>

                                <!-- View product -->
                                <a href="{{ route('customer.products.detail', $product->id) }}" class="mx-1 action-icon">
                                    <i class="mx-1 fas fa-eye"></i>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>


            @empty
                <div class="py-5 text-center col-12">
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
