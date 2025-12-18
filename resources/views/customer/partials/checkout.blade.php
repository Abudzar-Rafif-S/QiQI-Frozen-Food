@extends('customer.index')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h2 class="mb-4 text-center font-weight-bold" style="color:#8B0000;">Checkout</h2>

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="row">
                    <!-- Kolom Kiri: Informasi Pengiriman -->
                    <div class="col-md-6">
                        <div class="border-0 shadow-sm card">
                            <div class="py-3 bg-white card-header">
                                <h5 class="mb-0" style="color:#8B0000;">Informasi Pengiriman</h5>
                            </div>
                            <div class="card-body">
                                <form id="updateCustomerForm">
                                    @csrf
                                    <div class="form-group">
                                        <label>Nama Lengkap</label>
                                        <input type="text" class="form-control" value="{{ $customer->fullname ?? '' }}"
                                            disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Alamat Lengkap</label>
                                        <textarea name="address" class="form-control" rows="3" required>{{ $customer->address ?? '' }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Kota</label>
                                        <select name="city_id" class="form-control" required>
                                            <option value="">Pilih Kota</option>
                                            @foreach ($cities as $city)
                                                <option value="{{ $city->id }}"
                                                    {{ $customer?->city_id == $city->id ? 'selected' : '' }}>
                                                    {{ $city->city_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Nomor Telepon</label>
                                        <input type="text" class="form-control" value="{{ $customer->phone ?? '' }}"
                                            disabled>
                                        <small class="form-text text-muted">
                                            Pastikan nomor telepon sudah diisi di profil.
                                        </small>
                                    </div>
                                    <button type="submit" class="mt-2 btn btn-primary btn-block">
                                        Simpan & Hitung Ongkir
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Ringkasan Pesanan -->
                    <div class="col-md-6">
                        <div class="border-0 shadow-sm card h-100">
                            <div class="py-3 bg-white card-header">
                                <h5 class="mb-0" style="color:#8B0000;">Ringkasan Pesanan</h5>
                            </div>
                            <div class="card-body">
                                @php
                                    $subtotal = 0;
                                @endphp

                                @foreach ($order->items as $item)
                                    @php
                                        $subtotal += $item->subtotal;
                                    @endphp
                                    <div class="mb-2 d-flex justify-content-between">
                                        <div>
                                            <strong>{{ $item->product->product_name }}</strong>
                                            <br>
                                            <small class="text-muted">x{{ $item->qty }}</small>
                                            @if ($item->discounts > 0)
                                                <br><small class="text-success">Diskon
                                                    {{ number_format($item->discounts, 0) }}%</small>
                                            @endif
                                        </div>
                                        <div>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                                    </div>
                                    <hr>
                                @endforeach

                                <!-- Ongkos Kirim -->
                                <div class="mt-3 d-flex justify-content-between">
                                    <strong>Ongkos Kirim</strong>
                                    <span id="shipping-cost">Rp 0</span>
                                </div>

                                <!-- Total -->
                                <div class="mt-3 d-flex justify-content-between">
                                    <h5 class="mb-0">Total</h5>
                                    <h5 class="mb-0 text-success" id="grand-total">
                                        Rp {{ number_format($subtotal, 0, ',', '.') }}
                                    </h5>
                                </div>

                                <button id="pay-button" class="mt-4 btn btn-outline-primary btn-lg btn-block">
                                    <i class="fas fa-credit-card"></i> Bayar Sekarang
                                </button>

                                <a href="{{ route('customer.cart.index') }}" class="mt-3 btn btn-link text-muted">
                                    ← Kembali ke Keranjang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Midtrans Snap -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const invoice = '{{ $order->invoice_number }}';
            const baseSubtotal = {{ $subtotal }};
            let shippingCost = 0;
            let grandTotal = baseSubtotal;

            // Update customer
            document.getElementById('updateCustomerForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const cityId = this.querySelector('select[name="city_id"]').value;
                if (!cityId) {
                    alert('Silakan pilih kota.');
                    return;
                }
                const formData = new FormData(this);
                fetch("{{ route('customer.checkout.updateCustomer') }}", {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            // Setelah simpan, hitung ongkir otomatis
                            estimateShipping(cityId);
                        } else {
                            alert('Gagal menyimpan data.');
                        }
                    });
            });

            // Hitung ongkir
            function estimateShipping(cityId) {
                fetch("{{ route('customer.checkout.estimateShipping') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            city_id: cityId
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            shippingCost = data.shipping_cost;
                            grandTotal = baseSubtotal + shippingCost;
                            document.getElementById('shipping-cost').textContent = 'Rp ' + new Intl
                                .NumberFormat('id-ID').format(shippingCost);
                            document.getElementById('grand-total').textContent = 'Rp ' + new Intl.NumberFormat(
                                'id-ID').format(grandTotal);
                        }
                    });
            }

            // Bayar
            document.getElementById('pay-button').addEventListener('click', function() {
                const cityId = document.querySelector('select[name="city_id"]').value;
                if (!cityId) {
                    alert('Silakan pilih kota terlebih dahulu.');
                    return;
                }

                fetch("{{ route('customer.checkout.process') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            invoice_number: invoice
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success && data.snap_token) {
                            snap.pay(data.snap_token, {
                                onSuccess: () => {
                                    window.location.href =
                                        "{{ route('customer.orders.invoice', ['order' => $order->id]) }}";
                                },
                                onPending: () => window.location.href =
                                    "{{ route('customer.orders.index') }}",
                                onError: () => alert('Pembayaran gagal.')
                            });
                        } else {
                            alert(data.message || 'Gagal memproses checkout.');
                        }
                    });
            });

            // Jika kota sudah dipilih saat load, hitung ongkir
            @if ($customer?->city_id)
                estimateShipping({{ $customer->city_id }});
            @endif
        });
    </script>
@endsection
