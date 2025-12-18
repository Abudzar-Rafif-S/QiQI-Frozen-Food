<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Shipping;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // 1. Jumlah pesanan yang dibuat hari ini (semua)
        $ordersToday = Order::whereDate('created_at', now()->today())->count();

        // 2. Pendapatan hari ini: hanya dari pesanan yang SUDAH DITERIMA (shipping_status = 'accepted')
        $revenueToday = Order::whereDate('created_at', now()->today())
            ->whereHas('shipping', function ($query) {
                $query->where('shipping_status', 'accepted');
            })
            ->sum('gross_amount');

        // 3. Pesanan yang belum dikirim (shipping_status = 'pending')
        $pendingOrders = Shipping::where('shipping_status', 'pending')
            ->whereHas('order.payment', function ($query) {
                $query->where('payment_status', 'paid');
            })
            ->count();

        // 4. Pendapatan 1 tahun terakhir: hanya jika payment_status = 'paid'
        $revenueYearly = Order::whereHas('payment', function ($query) {
            $query->where('payment_status', 'paid')
                ->whereBetween('created_at', [now()->subYear(), now()]);
        })
            ->sum('gross_amount');

        // 5. Data bulanan untuk chart (12 bulan terakhir, berdasarkan tanggal PESANAN)
        $monthlyRevenue = Order::select(
            DB::raw('YEAR(orders.created_at) as year'),
            DB::raw('MONTH(orders.created_at) as month'),
            DB::raw('SUM(orders.gross_amount) as total')
        )
            ->whereHas('payment', function ($query) {
                $query->where('payment_status', 'paid');
            })
            ->whereBetween('orders.created_at', [
                now()->subMonths(11)->startOfMonth(),
                now()->endOfMonth()
            ])
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Siapkan labels dan data HANYA untuk bulan yang punya pendapatan
        $labels = [];
        $chartData = [];

        foreach ($monthlyRevenue as $item) {
            $labels[] = date('M Y', mktime(0, 0, 0, $item->month, 1, $item->year));
            $chartData[] = (float) $item->total;
        }

        // Jika tidak ada data sama sekali, tampilkan satu titik (mencegah error chart)
        if (empty($labels)) {
            $labels = ['Des 2025'];
            $chartData = [0.0];
        }

        // 6. 5 pesanan terbaru
        $recentOrders = Order::with(['customer', 'shipping'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'ordersToday',
            'revenueToday',
            'pendingOrders',
            'revenueYearly',
            'recentOrders',
            'labels',
            'chartData'
        ));
    }
}
