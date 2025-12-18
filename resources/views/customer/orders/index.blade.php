@extends('customer.index')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Daftar Pesanan</h2>

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

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>No. Invoice</th>
                        <th>Total</th>
                        <th>Status Pembayaran</th>
                        <th>Status Pengiriman</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td><strong>{{ $order->invoice_number }}</strong></td>
                            <td>Rp {{ number_format($order->gross_amount, 0, ',', '.') }}</td>
                            <td>
                                @php
                                    $paymentStatus = $order->payment?->payment_status ?? 'unknown';
                                @endphp
                                <span
                                    class="badge badge-{{ $paymentStatus === 'pending' ? 'warning' : ($paymentStatus === 'paid' ? 'success' : 'secondary') }}">
                                    {{ ucfirst($paymentStatus) }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $shippingStatus = $order->shipping?->shipping_status ?? 'unknown';
                                    $badgeClass = match ($shippingStatus) {
                                        'pending' => 'warning',
                                        'on_delivery' => 'info',
                                        'accepted' => 'success',
                                        default => 'secondary',
                                    };
                                    $displayStatus = match ($shippingStatus) {
                                        'on_delivery' => 'On Delivery',
                                        'accepted' => 'Accepted',
                                        'pending' => 'Pending',
                                        default => 'Unknown',
                                    };
                                @endphp
                                <span class="badge badge-{{ $badgeClass }}">{{ $displayStatus }}</span>
                            </td>
                            <td>
                                @if ($order->payment?->payment_status === 'pending')
                                    <button class="btn btn-outline-primary btn-sm btnPayAgain"
                                        data-order-id="{{ $order->id }}">
                                        Bayar Lagi
                                    </button>

                                    <form action="{{ route('customer.orders.cancel', $order->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                            onclick="return confirm('Batalkan pesanan ini?')">
                                            Batalkan
                                        </button>
                                    </form>
                                @elseif($order->payment?->payment_status === 'paid')
                                    <a href="{{ route('customer.orders.invoice', $order->id) }}"
                                        class="mb-1 btn btn-outline-primary btn-sm">
                                        <i class="fas fa-file-invoice"></i> Lihat Invoice
                                    </a>

                                    {{-- Tombol "Terima Barang" hanya muncul jika shipping status = on_delivery --}}
                                    @if ($order->shipping?->shipping_status === 'on_delivery')
                                        <form action="{{ route('customer.orders.accept-delivery', $order->id) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-primary btn-sm"
                                                onclick="return confirm('Konfirmasi bahwa barang telah diterima?')">
                                                <i class="fas fa-check"></i> Terima Barang
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center">
                                <i>Belum ada pesanan.</i>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Loading -->
    <div class="modal fade" id="payAgainModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Lanjutkan Pembayaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="text-center modal-body">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-2">Memproses token pembayaran...</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btnPayAgain').forEach(btn => {
                btn.addEventListener('click', function() {
                    const orderId = this.dataset.orderId;
                    const modal = new bootstrap.Modal(document.getElementById('payAgainModal'));
                    modal.show();

                    fetch(`/customer/orders/pay-again/${orderId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            modal.hide();
                            if (!data.success) {
                                alert(data.message || 'Gagal memuat pembayaran.');
                                return;
                            }

                            // ✅ Gunakan `snap_token` (snake_case)
                            snap.pay(data.snap_token, {
                                onSuccess: () => window.location.reload(),
                                onPending: () => window.location.reload(),
                                onError: () => alert('Pembayaran gagal.'),
                                onClose: () => alert('Pembayaran dibatalkan.')
                            });
                        })
                        .catch(err => {
                            modal.hide();
                            alert('Terjadi kesalahan. Coba lagi nanti.');
                            console.error(err);
                        });
                });
            });
        });
    </script>
@endpush
