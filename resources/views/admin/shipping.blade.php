@extends('admin.index')

@section('title', 'Manajemen Pengiriman')

@section('content')
    <!-- Page Heading -->
    <div class="mb-4 d-sm-flex align-items-center justify-content-between">
        <h1 class="mb-0 text-gray-800 h3">Daftar Order Customer</h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- DataTabel Pengiriman -->
    <div class="mb-4 shadow card">
        <div class="py-3 card-header">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pengiriman</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Invoice</th>
                            <th>Pelanggan</th>
                            <th>Alamat</th>
                            <th>Biaya Kirim</th>
                            <th>Status Pembayaran</th>
                            <th>Status Pengiriman</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($shippings as $shipping)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $shipping->order->invoice_number }}</td>
                                <td>
                                    {{ $shipping->order->customer?->fullname ?? '—' }}
                                </td>
                                <td>{{ Str::limit($shipping->address, 40) }}</td>
                                <td>Rp {{ number_format($shipping->shipping_cost, 0, ',', '.') }}</td>
                                <td>
                                    @php
                                        $paymentStatus = $shipping->order->payment->payment_status ?? 'unknown';
                                        $badgeClass = match ($paymentStatus) {
                                            'cancell' => 'badge-warning',
                                            'pending' => 'badge-info',
                                            'paid' => 'badge-success',
                                            default => 'badge-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $paymentStatus)) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $shippingStatus = $shipping->shipping_status;
                                        $badgeClass = match ($shippingStatus) {
                                            'pending' => 'badge-warning',
                                            'on_delivery' => 'badge-info',
                                            'accepted' => 'badge-success',
                                            default => 'badge-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $shippingStatus)) }}
                                    </span>
                                </td>
                                <td>
                                    <!-- Tombol Invoice (selalu muncul) -->
                                    <a href="{{ route('admin.shippings.invoice', $shipping->id) }}"
                                        class="mb-1 btn btn-info btn-sm">
                                        <i class="fas fa-file-invoice"></i> Invoice
                                    </a>

                                    @if ($shipping->shipping_status === 'pending' && optional($shipping->order->payment)->payment_status === 'paid')
                                        <form action="{{ route('admin.shippings.deliver', $shipping->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-sm"
                                                onclick="return confirm('Kirim pesanan ini? Status akan berubah menjadi \"On Delivery\".')">
                                                <i class="fas fa-truck"></i> Deliver
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-3 text-center">Tidak ada data pengiriman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
