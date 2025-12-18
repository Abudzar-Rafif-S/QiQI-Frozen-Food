@extends('customer.index')

@section('content')

    @php
        $customer = $navbarCustomer ?? null;
    @endphp

    <section id="cart-page" class="py-5">
        <div class="container">

            <h2 class="mb-4 text-center font-weight-bold" style="color:#8B0000;">
                Keranjang Belanja Anda
            </h2>

            @if ($carts->isEmpty())
                <div class="py-5 text-center">
                    <h5 class="text-muted">Keranjang masih kosong</h5>
                    <a href="{{ route('customer.products') }}" class="mt-3 btn btn-primary rounded-pill">
                        Belanja Sekarang
                    </a>
                </div>
            @else
                <div class="table-responsive cart-table-container">
                    <table class="table align-middle cart-table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Kuantitas</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $grandTotal = 0;
                            @endphp

                            @foreach ($carts as $cart)
                                @php
                                    $product = $cart->product;
                                    $price = $product->discount
                                        ? $product->price - ($product->price * $product->discount->value / 100)
                                        : $product->price;
                                    $subtotal = $price * $cart->quantity;
                                    $grandTotal += $subtotal;
                                @endphp

                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('products/' . $product->picture) }}" class="mr-3 cart-img"
                                                style="width:70px; height:70px; object-fit:cover;">
                                            <div>
                                                <strong class="product-name">{{ $product->product_name }}</strong>
                                                <div class="small text-muted">
                                                    @if ($product->discount)
                                                        Diskon {{ number_format($product->discount->value, 0) }}%
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="price">Rp {{ number_format($cart->product->price, 0, ',', '.') }}</span>
                                    </td>

                                    <td>
                                        <form action="{{ route('customer.cart.update', $cart->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" name="quantity" value="{{ $cart->quantity }}"
                                                min="1" class="form-control quantity-input" onchange="this.form.submit()">
                                        </form>
                                    </td>

                                    <td>
                                        <span class="subtotal">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                    </td>

                                    <td>
                                        <form action="{{ route('customer.cart.destroy', $cart->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus produk dari keranjang?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm rounded-pill">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Total dan Checkout -->
                <div class="mt-4 text-right">
                    <h4 class="grand-total-text">
                        Total Belanja:
                        <span class="grand-total">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                    </h4>

                    @if (!$customer || !$customer->city_id || !$customer->address || !$customer->phone)
                        <div class="mt-3">
                            <div class="alert alert-warning d-inline-block">
                                Lengkapi data profil (alamat, kota, dan nomor telepon) terlebih dahulu sebelum checkout.
                            </div>
                            <a href="{{ route('customer.profile.index') }}" class="mt-3 btn btn-secondary rounded-pill">
                                Lengkapi Profil
                            </a>
                        </div>
                    @else
                        <form action="{{ route('customer.cart.checkout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="px-4 mt-3 btn btn-primary btn-lg rounded-pill">
                                Lanjutkan ke Checkout
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        </div>
    </section>

@endsection
