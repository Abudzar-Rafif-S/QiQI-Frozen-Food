@extends('customer.index')

@section('content')
    <div class="container py-4">
        <div class="invoice-header d-flex justify-content-between align-items-center">
            <div>
                <img src="{{ asset('component/Logo.png') }}" alt="QIQI Frozen Food" class="logo">
                <h2 class="mt-2 invoice-title">QIQI Frozen Food</h2>
            </div>
            <div class="text-right">
                <h4>INVOICE</h4>
                <p class="mb-0"><strong>#{{ $order->invoice_number }}</strong></p>
                <p class="mb-0 text-muted">{{ \Carbon\Carbon::parse($order->created_at)->translatedFormat('d F Y') }}</p>
            </div>
        </div>

        <!-- Informasi Toko & Pelanggan -->
        <div class="mt-4 row">
            <div class="col-md-6">
                <h5 class="section-title">Detail Toko</h5>
                <div class="info-box">
                    <p class="mb-1"><strong>QIQI Frozen Food</strong></p>
                    <p class="mb-1">Jl. Patiunus gang nikita No.kav 9</p>
                    <p class="mb-1">Kota Pasuruan, Jawa Timur</p>
                    <p class="mb-1">+62 857-5537-8206</p>
                    <p class="mb-0">@agenfrozenfoodpasuruan</p>
                </div>
            </div>
            <div class="col-md-6">
                <h5 class="section-title">Detail Pelanggan</h5>
                <div class="info-box">
                    <p class="mb-1"><strong>{{ $customer->name }}</strong></p>
                    <p class="mb-1">{{ $customer->address }}</p>
                    <p class="mb-1">{{ $customer->city->city_name }}</p>
                    <p class="mb-1">Telepon: {{ $customer->phone }}</p>
                    <p class="mb-0">Email: {{ $customer->user->email }}</p>
                </div>
            </div>
        </div>

        <!-- Detail Pesanan -->
        <div class="mt-3 row">
            <div class="col-md-12">
                <h5 class="section-title">Detail Pesanan</h5>
                <div class="info-box">
                    <p class="mb-1">
                        <strong>Status Pesanan:</strong>
                        <span
                            class="badge
                        @if ($order->status === 'pending') badge-warning text-dark
                        @elseif($order->status === 'canceled') badge-danger
                        @else badge-success @endif
                    ">
                            {{ ucfirst($order->status) }}
                        </span>
                    </p>
                    <p class="mb-1">
                        <strong>Status Pembayaran:</strong>
                        <span
                            class="badge
                        @if ($order->payment?->payment_status === 'settlement') badge-success
                        @elseif($order->payment?->payment_status === 'pending') badge-warning text-dark
                        @else badge-secondary @endif
                    ">
                            {{ $order->payment?->payment_status ? ucfirst($order->payment->payment_status) : 'Belum tersedia' }}
                        </span>
                    </p>
                    <p class="mb-1"><strong>Metode Pembayaran:</strong> Midtrans</p>
                    <p class="mb-0"><strong>Tanggal Pesanan:</strong>
                        {{ \Carbon\Carbon::parse($order->created_at)->translatedFormat('d F Y, H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Daftar Produk -->
        <div class="mt-3 row">
            <div class="col-md-12">
                <h5 class="section-title">Daftar Produk</h5>
                <div class="table-responsive">
                    <table class="table table-bordered invoice-table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Berat (kg)</th>
                                <th>Harga Satuan</th>
                                <th>Kuantitas</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td>{{ $item->product?->name ?? 'Produk tidak tersedia' }}</td>
                                    <td>{{ $item->weight }} kg</td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td>{{ $item->qty }}</td>
                                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Ringkasan Pembayaran -->
        <div class="mt-4 row justify-content-end">
            <div class="col-md-5">
                <div class="info-box">
                    <div class="d-flex justify-content-between">
                        <span>Ongkos Kirim:</span>
                        <strong>Rp {{ number_format($order->shipping?->shipping_cost ?? 0, 0, ',', '.') }}</strong>
                    </div>
                    <hr>
                    <div class="mt-2 d-flex justify-content-between">
                        <span><strong>Total:</strong></span>
                        <strong>Rp {{ number_format($order->gross_amount, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Kembali -->
        <div class="mt-5 text-center">
            <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-primary">Kembali ke Dashboard</a>
        </div>
    </div>
@endsection
