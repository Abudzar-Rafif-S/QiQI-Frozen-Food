@extends('landing.index')

@section('content')

    <div class="container mt-4">

        <h2 class="mb-4 text-center font-weight-bold" style="color:#8B0000;">Hasil Pencarian: "{{ $keyword }}"</h2>

        @if ($products->count() == 0)
            <p>Tidak ada produk ditemukan.</p>
        @else
            <div class="row">
                @foreach ($products as $product)
                    <div class="col-md-3 mb-4">
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
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $products->links() }}
            </div>
        @endif
    </div>

@endsection
