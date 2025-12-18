<!-- ================= DISCOUNT SECTION ================= -->
<section id="discount-section" class="py-5" style="background: #F4F6FF;">
    <div class="container">

        <div class="text-center mb-4">
            <h2 class="font-weight-bold" style="color:#8B0000;">🔥 Special Discounts</h2>
            <p class="text-muted">Nikmati penawaran terbatas dengan harga terbaik</p>
        </div>

        <!-- BOOTSTRAP 4 CAROUSEL -->
        <div id="discountCarousel" class="carousel slide" data-ride="carousel">

            <div class="carousel-inner">

                @foreach ($discountedProducts->chunk(3) as $chunkIndex => $chunk)
                    <div class="carousel-item {{ $chunkIndex == 0 ? 'active' : '' }}">
                        <div class="row">

                            @foreach ($chunk as $product)
                                <div class="mb-4 col-md-4">

                                    <div class="border-0 shadow-sm card">

                                        <div class="position-relative">

                                            {{-- Gambar --}}
                                            <img src="{{ $product->picture ? asset('products/' . $product->picture) : 'https://via.placeholder.com/400x300' }}"
                                                class="d-block w-100" style="height: 240px; object-fit: cover;">

                                            {{-- Badge Diskon --}}
                                            <span class="rounded-pill discount-badge">
                                                -{{ $product->discount->value }}%
                                            </span>

                                        </div>

                                        {{-- Body --}}
                                        <div class="card-body">

                                            <h5 class="card-title">{{ $product->product_name }}</h5>

                                            @php
                                                $discountedPrice =
                                                    $product->price -
                                                    ($product->price * $product->discount->value) / 100;
                                            @endphp

                                            <div class="mb-3 text-center product-price">
                                                <span class="old-price">
                                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                                </span>

                                                <span class="new-price">
                                                    Rp {{ number_format($discountedPrice, 0, ',', '.') }}
                                                </span>
                                            </div>

                                            <a href="{{ route('guest.products.detail', $product->id) }}"
                                                class="btn btn-outline-primary rounded-pill btn-block">
                                                Lihat Detail
                                            </a>

                                        </div>

                                    </div>

                                </div>
                            @endforeach

                        </div>
                    </div>
                @endforeach

            </div>

            {{-- Controls --}}
            <a class="carousel-control-prev" href="#discountCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </a>

            <a class="carousel-control-next" href="#discountCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon"></span>
            </a>

        </div>

    </div>
</section>
