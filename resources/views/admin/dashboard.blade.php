@extends('admin.index')

@section('title', 'Dashboard - Qiqi Frozen Food')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="mb-4 d-sm-flex align-items-center justify-content-between">
            <h1 class="mb-0 text-gray-800 h3">Dashboard Admin</h1>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <!-- Pesanan Hari Ini -->
            <div class="mb-4 col-xl-3 col-md-6">
                <div class="py-2 shadow card border-left-primary h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="mr-2 col">
                                <div class="mb-1 text-xs font-weight-bold text-primary text-uppercase">
                                    Pesanan Hari Ini
                                </div>
                                <div class="mb-0 text-gray-800 h5 font-weight-bold">{{ $ordersToday }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="text-gray-300 fas fa-shopping-cart fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pendapatan Hari Ini -->
            <div class="mb-4 col-xl-3 col-md-6">
                <div class="py-2 shadow card border-left-success h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="mr-2 col">
                                <div class="mb-1 text-xs font-weight-bold text-success text-uppercase">
                                    Pendapatan Hari Ini
                                </div>
                                <div class="mb-0 text-gray-800 h5 font-weight-bold">
                                    Rp {{ number_format($revenueToday, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="text-gray-300 fas fa-dollar-sign fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pesanan Belum Diproses -->
            <div class="mb-4 col-xl-3 col-md-6">
                <div class="py-2 shadow card border-left-warning h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="mr-2 col">
                                <div class="mb-1 text-xs font-weight-bold text-warning text-uppercase">
                                    Pesanan Belum Diproses
                                </div>
                                <div class="mb-0 text-gray-800 h5 font-weight-bold">{{ $pendingOrders }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="text-gray-300 fas fa-exclamation-triangle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pendapatan (12 Bulan) -->
            <div class="mb-4 col-xl-3 col-md-6">
                <div class="py-2 shadow card border-left-info h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="mr-2 col">
                                <div class="mb-1 text-xs font-weight-bold text-info text-uppercase">
                                    Pendapatan (12 Bulan)
                                </div>
                                <div class="mb-0 text-gray-800 h5 font-weight-bold">
                                    Rp {{ number_format($revenueYearly, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="text-gray-300 fas fa-chart-line fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings Overview -->
        <div class="row">
            <div class="col-xl-12">
                <div class="mb-4 shadow card">
                    <div class="py-3 card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Pendapatan 12 Bulan Terakhir</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="myAreaChart" data-labels='@json($labels)'
                                data-values='@json($chartData)'>
                            </canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders Table -->
        <div class="row">
            <div class="col-xl-12">
                <div class="mb-4 shadow card">
                    <div class="py-3 card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Pesanan Terbaru</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Pelanggan</th>
                                        <th>Tanggal</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentOrders as $order)
                                        <tr>
                                            <td>{{ $order->id }}</td>
                                            <td>{{ $order->customer->fullname ?? '–' }}</td>
                                            <td>{{ $order->created_at->format('d M Y') }}</td>
                                            <td>Rp {{ number_format($order->gross_amount, 0, ',', '.') }}</td>
                                            <td>
                                                @php
                                                    $status = $order->shipping?->shipping_status ?? 'unknown';
                                                    $badgeClass = match ($status) {
                                                        'pending' => 'badge badge-warning',
                                                        'on_delivery' => 'badge badge-info',
                                                        'accepted' => 'badge badge-success',
                                                        default => 'badge badge-secondary',
                                                    };
                                                @endphp
                                                <span class="{{ $badgeClass }}">
                                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                                    class="btn btn-sm btn-primary">
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada pesanan</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        if (typeof Chart !== 'undefined') {
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('myAreaChart');
                if (ctx) {
                    const labels = ["Dec 2025"];
                    const data = [465900];

                    new Chart(ctx, {
                        type: 'line',
                        { // ✅ Tambahkan "" DI SINI
                            labels: labels,
                            datasets: [{
                                label: 'Pendapatan (Rp)',
                                data, // ✅ Tambahkan "" DI SINI
                                borderColor: 'rgba(78, 115, 223, 1)',
                                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.3
                            }]
                        },
                        options: {
                            maintainAspectRatio: false,
                            responsive: true,
                            spanGaps: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return 'Rp ' + value.toLocaleString('id-ID');
                                        }
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top'
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return 'Pendapatan: Rp ' + context.parsed.y.toLocaleString(
                                                'id-ID');
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            });
        }
    </script>
@endpush
