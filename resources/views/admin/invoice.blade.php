@extends('admin.index')

@section('title', 'Invoice - ' . $shipping->order->invoice_number)

@section('content')
<div class="mb-4 d-sm-flex align-items-center justify-content-between">
    <h1 class="mb-0 text-gray-800 h3">Detail Pesanan</h1>
    <a href="{{ route('admin.shippings.index') }}" class="shadow-sm d-none d-sm-inline-block btn btn-sm btn-secondary">
        <i class="fas fa-arrow-left fa-sm"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-xl-8">
        <div class="mb-4 shadow card">
            <div class="py-3 card-header">
                <h6 class="m-0 font-weight-bold text-primary">Invoice #{{ $shipping->order->invoice_number }}</h6>
            </div>
            <div class="card-body">
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <h6 class="font-weight-bold">Informasi Pelanggan</h6>
                        <p class="mb-1">
                            <strong>Nama:</strong> {{ $shipping->order->customer->fullname ?? '—' }}<br>
                            <strong>Email:</strong> {{ $shipping->order->customer->user->email ?? '—' }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="font-weight-bold">Status Pesanan</h6>
                        <p class="mb-1">
                            <span class="badge badge-info">Pembayaran: {{ $shipping->order->payment?->payment_status ?? 'Pending' }}</span><br>
                            <span class="badge {{
                                match($shipping->shipping_status) {
                                    'pending' => 'badge-warning',
                                    'on_delivery' => 'badge-info',
                                    'accepted' => 'badge-success',
                                    default => 'badge-secondary'
                                }
                            }}">
                                Pengiriman: {{ ucfirst(str_replace('_', ' ', $shipping->shipping_status)) }}
                            </span>
                        </p>
                    </div>
                </div>

                <h6 class="mt-4 mb-3 font-weight-bold">Detail Produk</h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shipping->order->items as $item)
                                <tr>
                                    <td>{{ $item->product->product_name ?? 'Produk dihapus' }}</td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td>{{ $item->qty }}</td>
                                    <td>Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-right">Subtotal:</th>
                                <td>Rp {{ number_format($shipping->order->gross_amount, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th colspan="3" class="text-right">Biaya Kirim:</th>
                                <td>Rp {{ number_format($shipping->shipping_cost, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th colspan="3" class="text-right">Total:</th>
                                <td><strong>Rp {{ number_format($shipping->order->gross_amount + $shipping->shipping_cost, 0, ',', '.') }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="mb-4 shadow card">
            <div class="py-3 card-header">
                <h6 class="m-0 font-weight-bold text-primary">Alamat Pengiriman</h6>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $shipping->address }}</p>
            </div>
        </div>

        @if ($shipping->order->payment)
            <div class="mb-4 shadow card">
                <div class="py-3 card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Pembayaran</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0"><strong>Tanggal:</strong> {{ $shipping->order->payment->created_at?->format('d M Y H:i') ?? '—' }}</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
