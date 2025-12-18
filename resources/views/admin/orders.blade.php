@extends('admin.index')

@section('title', 'Detail Pesanan #' . $order->id)

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="mb-4 d-sm-flex align-items-center justify-content-between">
            <h1 class="mb-0 text-gray-800 h3">Detail Pesanan #{{ $order->id }}</h1>
            <a href="{{ route('admin.dashboard') }}" class="shadow-sm d-none d-sm-inline-block btn btn-sm btn-secondary">
                &larr; Kembali ke Dashboard
            </a>
        </div>

        <!-- Order Summary Card -->
        <div class="row">
            <div class="col-lg-8">
                <div class="mb-4 shadow card">
                    <div class="py-3 card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Informasi Pesanan</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Tanggal Pesan:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
                        <p><strong>Invoice:</strong> {{ $order->invoice_number ?? '–' }}</p>
                        <p><strong>Total Bayar:</strong> Rp {{ number_format($order->gross_amount, 0, ',', '.') }}</p>

                        <!-- Status Pengiriman -->
                        @if ($order->shipping)
                            @php
                                $status = $order->shipping->shipping_status;
                                $badgeClass = match ($status) {
                                    'pending' => 'badge-warning',
                                    'on_delivery' => 'badge-info',
                                    'accepted' => 'badge-success',
                                    default => 'badge-secondary',
                                };
                                $statusLabel = match ($status) {
                                    'pending' => 'Menunggu Pengiriman',
                                    'on_delivery' => 'Sedang Dikirim',
                                    'accepted' => 'Pesanan Diterima',
                                    default => 'Tidak Diketahui',
                                };
                            @endphp
                            <p><strong>Status Pengiriman:</strong>
                                <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                            </p>
                        @else
                            <p><strong>Status Pengiriman:</strong> <span class="badge badge-secondary">Belum Ada Data</span>
                            </p>
                        @endif

                        <!-- Status Pembayaran -->
                        @if ($order->payment)
                            @php
                                $paymentStatus = $order->payment->payment_status;
                                $paymentBadgeClass = match ($paymentStatus) {
                                    'cancell' => 'badge-warning',
                                    'pending' => 'badge-info',
                                    'paid' => 'badge-success',
                                    default => 'badge-secondary',
                                };
                                $paymentStatusLabel = ucfirst(str_replace('_', ' ', $paymentStatus));
                            @endphp
                            <p><strong>Status Pembayaran:</strong>
                                <span class="badge {{ $paymentBadgeClass }}">{{ $paymentStatusLabel }}</span>
                            </p>
                        @else
                            <p><strong>Status Pembayaran:</strong> <span class="badge badge-secondary">Tidak
                                    Diketahui</span></p>
                        @endif
                    </div>
                </div>

                <!-- Produk yang Dibeli -->
                <div class="mb-4 shadow card">
                    <div class="py-3 card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Detail Produk</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th>Harga Satuan</th>
                                        <th>Jumlah</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td>
                                                {{ $item->product->name ?? 'Produk dihapus' }}
                                                @if ($item->product && $item->product->photo)
                                                    <br>
                                                    <img src="{{ asset('storage/' . $item->product->photo) }}"
                                                        alt="{{ $item->product->name }}" width="50" class="mt-1">
                                                @endif
                                            </td>
                                            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                            <td>{{ $item->qty }}</td>
                                            <td>Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer & Action Panel -->
            <div class="col-lg-4">
                <!-- Customer Info -->
                <div class="mb-4 shadow card">
                    <div class="py-3 card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Pelanggan</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Nama:</strong> {{ $order->customer->fullname ?? '–' }}</p>
                        <p><strong>Email:</strong> {{ $order->customer->user->email ?? '–' }}</p>
                    </div>
                </div>

                <!-- Admin Action -->
                <div class="mb-4 shadow card">
                    <div class="py-3 card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Aksi Admin</h6>
                    </div>
                    <div class="card-body">
                        @if ($order->shipping)
                            @if ($order->shipping->shipping_status === 'pending')
                                @if ($order->payment && $order->payment->payment_status === 'paid')
                                    <form action="{{ route('admin.shippings.deliver', $order->shipping->id) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-info btn-block"
                                            onclick="return confirm('Yakin ingin mengubah status menjadi Sedang Dikirim?')">
                                            <i class="fas fa-truck"></i> Kirim Pesanan
                                        </button>
                                    </form>
                                @else
                                    <p class="text-muted">
                                        <i class="fas fa-exclamation-triangle text-warning"></i>
                                        Pesanan hanya dapat dikirim setelah pembayaran berhasil.
                                    </p>
                                @endif
                            @elseif($order->shipping->shipping_status === 'on_delivery')
                                <p class="text-muted">Menunggu konfirmasi penerimaan dari pelanggan.</p>
                            @elseif($order->shipping->shipping_status === 'accepted')
                                <p class="text-success"><i class="fas fa-check-circle"></i> Pesanan telah diterima
                                    pelanggan.</p>
                            @endif
                        @else
                            <p class="text-danger">Data pengiriman belum tersedia.</p>
                        @endif

                        <!-- Tombol Cetak Invoice -->
                        @if ($order->shipping)
                            <a href="{{ route('admin.shippings.invoice', $order->shipping->id) }}" target="_blank"
                                class="mt-2 btn btn-outline-primary btn-block">
                                <i class="fas fa-print"></i> Cetak Invoice
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
