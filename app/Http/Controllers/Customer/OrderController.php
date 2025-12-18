<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Shipping;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\CheckoutService;

class OrderController extends Controller
{
    public function index()
    {
        $customer = Auth::user()->customer;
        $orders = Order::with(['payment', 'shipping'])
            ->where('customer_id', $customer->id)
            ->latest()
            ->get();

        return view('customer.orders.index', compact('orders'));
    }

    public function payAgain(Request $request, Order $order)
    {
        $customer = Auth::user()->customer;

        // 1. Pastikan order milik customer
        if ($order->customer_id !== $customer->id) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak.'
            ], 403);
        }

        // 2. Pastikan status pembayaran masih 'pending'
        $paymentStatus = $order->payment?->payment_status ?? 'unknown';
        if ($paymentStatus !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Order tidak dalam status menunggu pembayaran.'
            ], 400);
        }

        // 3. Validasi expired: maksimal 60 menit sejak dibuat
        $expiredAt = Carbon::parse($order->created_at)->addMinutes(60);
        if (Carbon::now()->greaterThan($expiredAt)) {
            return response()->json([
                'success' => false,
                'message' => 'Waktu pembayaran telah habis.'
            ], 400);
        }

        // 4. Generate token baru
        $checkoutService = new CheckoutService();
        $snapToken = $checkoutService->getSnapToken($order);

        // 5. Simpan ke payment
        if ($order->payment) {
            $order->payment->update(['snap_token' => $snapToken]);
        }

        // 6. Kembalikan respons
        return response()->json([
            'success' => true,
            'snap_token' => $snapToken // ✅ snake_case sesuai dokumentasi
        ]);
    }

    public function cancel(Order $order)
    {
        $customerId = Auth::user()->customer->id;

        if ($order->customer_id != $customerId) {
            abort(403);
        }

        $expiredAt = Carbon::parse($order->created_at)->addMinutes(60);
        if (Carbon::now()->greaterThan($expiredAt)) {
            return redirect()->back()->with('error', 'Pesanan sudah expired, tidak bisa dibatalkan.');
        }

        $order->update(['status' => 'canceled']);
        if ($order->payment) {
            $order->payment->update(['payment_status' => 'canceled']);
        }

        return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    public function showInvoice(Order $order)
    {
        $customer = Auth::user()->customer;
        if ($order->customer_id !== $customer->id) {
            abort(403, 'Akses ditolak.');
        }

        $order->load(['items.product', 'payment', 'shipping']);
        return view('customer.orders.invoice', compact('order', 'customer'));
    }

    public function acceptDelivery(Request $request, Order $order)
    {
        $customer = Auth::user()->customer;

        // 1. Pastikan order milik customer
        if ($order->customer_id !== $customer->id) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        // 2. Pastikan shipping ada
        if (!$order->shipping) {
            return redirect()->back()->with('error', 'Data pengiriman tidak ditemukan.');
        }

        $shipping = $order->shipping;

        // 3. Validasi: hanya bisa accept jika status = on_delivery
        if ($shipping->shipping_status !== 'on_delivery') {
            return redirect()->back()->with('error', 'Pesanan belum dalam status pengiriman.');
        }

        // 4. Update status ke accepted
        $shipping->shipping_status = 'accepted';
        $shipping->save();

        return redirect()->back()->with('success', 'Pesanan berhasil dikonfirmasi telah diterima.');
    }
}
