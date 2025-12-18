<section id="latest-products" class="py-5">
    <div class="container">
        {{-- Judul dan deskripsi --}}
        <div class="mb-4 text-center">
            <h2 class="font-weight-bold" style="color:#8B0000;">Produk Terbaru</h2>
            <p class="text-muted">Temukan produk terbaru dengan kualitas terbaik dan harga menarik.</p>
        </div>

        {{-- Grid produk --}}
        <div class="row">
            @foreach ($latestProducts as $product)
                <div class="mb-4 col-6 col-md-4 col-lg-3">
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
                                <form action="{{ route('customer.wishlist.store') }}" method="POST"
                                    class="p-0 m-0 mx-1">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="action-icon">
                                        <i class="mx-1 fas fa-heart"></i>
                                    </button>
                                </form>

                                <!-- View product -->
                                <a href="{{ route('customer.products.detail', $product->id) }}"
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
</section>
