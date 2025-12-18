@extends('landing.index')
@section('title', $product->product_name)

@section('content')
    <div class="container py-5">
        <div class="row">
            {{-- Gambar Produk --}}
            <div class="mb-4 col-lg-6">
                <div class="product-gallery">
                    <img src="{{ asset('products/' . $product->picture) }}" class="rounded img-fluid"
                        alt="{{ $product->product_name }}">
                </div>
            </div>

            {{-- Detail Produk --}}
            <div class="col-lg-6">
                <h2 class="fw-bold" style="color:#8B0000;">{{ $product->product_name }}</h2>
                <p class="text-muted">{{ $product->category->name }} | {{ $product->brand->name }}</p>

                @php
                    $hasDiscount = $product->discount && $product->discount->value > 0;
                @endphp

                @if ($hasDiscount)
                    @php
                        $discountValue = $product->discount->value; // persen
                        $originalPrice = $product->price;
                        $discountedPrice = $originalPrice - ($originalPrice * $discountValue) / 100;
                    @endphp

                    <h4 class="font-weight-bold" style="color:#8B0000;">
                        Rp {{ number_format($discountedPrice, 0, ',', '.') }}
                    </h4>

                    <small class="text-decoration-line-through text-muted">
                        Rp {{ number_format($originalPrice, 0, ',', '.') }}
                        ({{ $discountValue }}% OFF)
                    </small>
                @else
                    <h4 class="text-dark">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </h4>
                @endif

                <p class="mt-3">{{ $product->description }}</p>

                {{-- Input Quantity & Add to Cart --}}
                <div class="countainer-fluid">
                    <div class="row">
                        <a href="{{ route('login') }}" class="px-4 btn btn-primary ms-3"><i
                                class="fas fa-shopping-cart"></i> Add to Cart</a>
                        <a href="{{ route('login') }}" class="ml-auto btn btn-outline-primary"><i
                                class="fas fa-heart"></i></a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tampilan Review --}}
        @if ($product->reviews->count() > 0)
            <div class="mt-5">
                <h4 class="mb-4 font-weight-bold" style="color:#8B0000;">Ulasan Pelanggan</h4>
                <ul class="list-group list-group-flush">
                    @foreach ($product->reviews as $review)
                        <li class="list-group-item">
                            <strong>{{ $review->customer->fullname ?? 'Anonymous' }}</strong>
                            <p class="mb-0">{{ $review->comment }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Produk Terkait --}}
        <div class="mt-5">
            <h4 class="mb-4 font-weight-bold" style="color:#8B0000;">Produk Terkait</h4>
            <div class="row">
                @foreach ($product->category->products->where('id', '!=', $product->id)->take(4) as $related)
                    <div class="mb-4 col-md-3">
                        <div class="product-card">
                            <div class="product-image">
                                <img src="{{ asset('products/' . $related->picture) }}"
                                    alt="{{ $related->product_name }}" />
                            </div>

                            <div class="product-body">
                                <h5 class="text-center product-title">{{ $related->product_name }}</h5>

                                <div class="text-center product-price">
                                    @if ($related->discount)
                                        @php
                                            $diskon = $related->discount->value;
                                            $hargaDiskon = $related->price - ($related->price * $diskon) / 100;
                                        @endphp
                                        <span class="old-price">Rp {{ number_format($related->price, 0, ',', '.') }}</span>
                                        <span class="new-price">Rp {{ number_format($hargaDiskon, 0, ',', '.') }}</span>
                                    @else
                                        <span class="new-price">Rp {{ number_format($related->price, 0, ',', '.') }}</span>
                                    @endif
                                </div>

                                <div class="gap-3 mt-3 product-actions d-flex justify-content-center">
                                    <form action="{{ route('customer.cart.store') }}" method="POST" class="p-0 m-0 mx-1">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $related->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="action-icon">
                                            <i class="mx-1 fas fa-shopping-cart"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('customer.wishlist.store') }}" method="POST"
                                        class="p-0 m-0 mx-1">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $related->id }}">
                                        <button type="submit" class="action-icon">
                                            <i class="mx-1 fas fa-heart"></i>
                                        </button>
                                    </form>

                                    <a href="{{ route('customer.products.detail', $related->id) }}"
                                        class="mx-1 action-icon">
                                        <i class="mx-1 fas fa-eye"></i>
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
