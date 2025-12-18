@extends('customer.index')

@section('content')
<section id="wishlist-section" class="py-5">
    <div class="container">

        {{-- Title --}}
        <div class="mb-4 text-center">
            <h2 class="font-weight-bold" style="color:#8B0000;"> Wishlist Anda</h2>
            <p class="text-muted">Simpan produk favorit Anda untuk dibeli nanti.</p>
        </div>

        {{-- Jika wishlist kosong --}}
        @if($wishlists->isEmpty())
            <div class="py-5 text-center">
                <img src="https://cdn-icons-png.flaticon.com/512/4076/4076503.png"
                     width="120" class="mb-3">
                <h5 class="text-muted">Wishlist masih kosong</h5>
                <a href="{{ route('customer.products') }}" class="mt-3 btn btn-primary rounded-pill">
                    Jelajahi Produk
                </a>
            </div>
        @else
            <div class="row">

                @foreach ($wishlists as $wishlist)
                    @php
                        $product = $wishlist->product;
                        $hasDiscount = $product->discount ? true : false;
                        $discountValue = $hasDiscount ? $product->discount->value : 0;

                        $discountedPrice = $hasDiscount
                            ? $product->price - ($product->price * $discountValue / 100)
                            : $product->price;
                    @endphp

                    <div class="mb-4 col-6 col-md-4 col-lg-3">
                        <div class="border-0 shadow-sm card product-card h-100">

                            {{-- Gambar --}}
                            <div class="position-relative">
                                <img
                                    src="{{ $product->picture ? asset('products/' . $product->picture) : 'https://via.placeholder.com/300x200' }}"
                                    class="card-img-top"
                                    style="height: 200px; object-fit: cover;"
                                >

                                {{-- Badge Diskon --}}
                                @if($hasDiscount)
                                    <span class="discount-badge">
                                        -{{ $discountValue }}%
                                    </span>
                                @endif
                            </div>

                            {{-- Card Body --}}
                            <div class="text-center card-body d-flex flex-column">

                                {{-- Nama --}}
                                <h6 class="font-weight-bold">{{ $product->product_name }}</h6>

                                {{-- Harga --}}
                                <div class="mb-2 product-price">
                                    @if($hasDiscount)
                                        <span class="old-price">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </span>
                                        <span class="new-price">
                                            Rp {{ number_format($discountedPrice, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="new-price">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Tombol Aksi --}}
                                <div class="mt-auto">

                                    {{-- View Detail --}}
                                    <a href="{{ route('customer.products.detail', $product->id) }}"
                                       class="mb-2 btn btn-outline-primary btn-sm rounded-pill btn-block">
                                        Lihat Detail
                                    </a>

                                    {{-- Remove Wishlist --}}
                                    <form action="{{ route('customer.wishlist.destroy', $wishlist->id) }}"
                                          method="POST" onsubmit="return confirm('Hapus dari wishlist?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-outline-danger btn-sm rounded-pill btn-block">
                                            Hapus
                                        </button>
                                    </form>

                                </div>

                            </div>
                        </div>
                    </div>

                @endforeach

            </div>
        @endif

    </div>
</section>
@endsection
