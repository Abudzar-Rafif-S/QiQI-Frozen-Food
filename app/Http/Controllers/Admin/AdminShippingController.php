<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shipping;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminShippingController extends Controller
{
    public function index()
    {
        $shippings = Shipping::with('order.customer.user')->orderBy('created_at', 'desc')->get();
        return view('admin.shipping', compact('shippings'));
    }

    public function deliver(Request $request, string $id): RedirectResponse
    {
        $shipping = Shipping::findOrFail($id);

        if ($shipping->shipping_status !== 'pending') {
            Session::flash('error', 'Hanya pengiriman dengan status "Pending" yang dapat diubah.');
            return back();
        }

        $shipping->shipping_status = 'on_delivery';
        $shipping->save();

        Session::flash('success', 'Status pengiriman berhasil diperbarui menjadi "On Delivery".');
        return back();
    }

    public function showInvoice(Shipping $shipping)
    {
        // Load relasi yang dibutuhkan untuk invoice
        $shipping->load([
            'order.customer.user',
            'order.items.product', // pastikan OrderItem memiliki relasi ke Product
            'order.payment'
        ]);

        return view('admin.invoice', compact('shipping'));
    }

    public function showOrder($orderId)
    {
        $order = Order::with(['customer', 'shipping', 'items.product'])->findOrFail($orderId);
        return view('admin.orders', compact('order'));
    }
}
