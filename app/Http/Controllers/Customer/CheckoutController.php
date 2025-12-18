<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Shipping;
use App\Models\Customer;
use App\Models\ShippingRate;
use App\Models\City;

use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;
use Midtrans\Notification;

class CheckoutController extends Controller
{
    public function __construct()
    {
        MidtransConfig::$serverKey = config('midtrans.server_key');
        MidtransConfig::$isProduction = config('midtrans.is_production', false);
        MidtransConfig::$isSanitized = true;
        MidtransConfig::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Langkah 1: Buat draft order dari keranjang.
     * Hanya membuat Order + OrderItem (tanpa ongkir, shipping, payment).
     */
    public function cartCheckout(Request $request)
    {
        try {
            $user = Auth::user();
            $customer = $user->customer;

            if (!$customer) {
                return back()->with('error', 'Profil customer tidak ditemukan.');
            }

            $carts = Cart::with(['product.discount'])
                ->where('customer_id', $customer->id)
                ->get();

            if ($carts->isEmpty()) {
                return back()->with('error', 'Keranjang Anda kosong.');
            }

            // Validasi produk & stok
            foreach ($carts as $item) {
                if (!$item->product) {
                    return back()->with('error', 'Produk tidak valid dalam keranjang.');
                }
                if ($item->product->stock < $item->quantity) {
                    return back()->with('error', 'Stok "' . $item->product->product_name . '" tidak mencukupi.');
                }
            }

            $summary = $this->calculateCartSummary($carts);
            $subtotal = $summary['total_items_price'];

            DB::beginTransaction();

            $order = Order::create([
                'customer_id'    => $customer->id,
                'invoice_number' => 'INV-' . strtoupper(Str::uuid()->toString()),
                'gross_amount'   => round($subtotal, 2),
                'status'         => 'draft',
            ]);

            // Buat order items (hanya di sini!)
            foreach ($carts as $cart) {
                $unitPrice = $this->computeProductUnitPrice($cart->product);
                $qty = (int) $cart->quantity;
                $lineTotal = $unitPrice * $qty;
                $discountValue = $cart->product->discount?->value ?? 0;

                $order->items()->create([
                    'product_id' => $cart->product_id,
                    'weight'     => (float) $cart->product->weight,
                    'price'      => round($unitPrice, 2),
                    'qty'        => $qty,
                    'discounts'  => (float) $discountValue,
                    'subtotal'   => round($lineTotal, 2),
                ]);
            }

            DB::commit();

            return redirect()->route('customer.checkout.show', $order->invoice_number)
                ->with('success', 'Pesanan sementara dibuat. Silakan lengkapi data untuk pembayaran.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('cartCheckout error: ' . $e->getMessage());
            return back()->with('error', 'Gagal membuat pesanan sementara.');
        }
    }

    /**
     * Langkah 2: Tampilkan halaman checkout berdasarkan draft order.
     */
    public function showOrder($invoice)
    {
        try {
            $user = Auth::user();
            $customer = $user->customer;
            $cities = City::all();

            $order = Order::with(['customer', 'items.product']) // ✅ 'items', bukan 'order_items'
                ->where('invoice_number', $invoice)
                ->where('customer_id', $customer->id)
                ->firstOrFail();

            return view('customer.partials.checkout', compact('user', 'customer', 'order', 'cities'));
        } catch (\Exception $e) {
            return back()->with('error', 'Order tidak ditemukan.');
        }
    }
    /**
     * AJAX: Hitung estimasi ongkir dari keranjang (untuk tampilan di showOrder).
     */
    public function estimateShipping(Request $request)
    {
        $request->validate(['city_id' => 'required|exists:cities,id']);

        $customer = Auth::user()->customer;
        $cartItems = Cart::with(['product.discount'])
            ->where('customer_id', $customer->id)
            ->get();

        $summary = $this->calculateCartSummary($cartItems);
        $shippingRate = ShippingRate::where('city_id', $request->city_id)->first();

        if (!$shippingRate) {
            return response()->json([
                'success' => false,
                'message' => 'Tarif pengiriman tidak tersedia untuk kota tersebut.'
            ], 422);
        }

        $shippingCost = $summary['total_weight_kg'] * (float) $shippingRate->price_per_kg;
        $grandTotal = $summary['total_items_price'] + $shippingCost;

        return response()->json([
            'success'      => true,
            'shipping_cost' => round($shippingCost, 2),
            'grand_total'   => round($grandTotal, 2),
        ]);
    }

    /**
     * AJAX: Update data customer (alamat & kota).
     */
    public function updateCustomerInfo(Request $request)
    {
        $request->validate([
            'city_id' => 'required|exists:cities,id',
            'address' => 'required|string',
        ]);

        $customer = Customer::updateOrCreate(
            ['user_id' => Auth::user()->id],
            [
                'city_id' => $request->city_id,
                'address' => $request->address,
            ]
        );

        return response()->json(['success' => true, 'customer' => $customer]);
    }

    /**
     * Langkah 3: Proses checkout akhir berdasarkan draft order.
     * TIDAK membuat OrderItem lagi — hanya update order, buat shipping & payment.
     */
    public function checkout(Request $request)
    {
        $user = $request->user();
        $customer = Customer::where('user_id', $user->id)->first();

        // Validasi kelengkapan data customer
        if (!$customer || !$customer->city_id || !$customer->address || !$customer->phone) {
            return response()->json([
                'success' => false,
                'message' => 'Data customer belum lengkap (kota, alamat, atau telepon).'
            ], 422);
        }

        $shippingRate = ShippingRate::where('city_id', $customer->city_id)->first();
        if (!$shippingRate) {
            return response()->json([
                'success' => false,
                'message' => 'Tarif pengiriman tidak tersedia untuk kota Anda.'
            ], 422);
        }

        $invoice = $request->input('invoice_number');
        if (!$invoice) {
            return response()->json(['success' => false, 'message' => 'Nomor invoice tidak ditemukan.'], 422);
        }

        $order = Order::with(['items.product'])
            ->where('invoice_number', $invoice)
            ->where('customer_id', $customer->id)
            ->where('status', 'draft')
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Pesanan tidak ditemukan atau sudah diproses.'], 422);
        }

        // Hitung ulang dari OrderItem (bukan Cart!)
        $subtotal = 0;
        $totalWeightGram = 0;
        foreach ($order->items as $item) {
            $subtotal += $item->subtotal;
            $totalWeightGram += $item->weight * $item->qty;
        }

        $totalWeightKg = $totalWeightGram / 1000;
        $shippingCost = $totalWeightKg * (float) $shippingRate->price_per_kg;
        $grandTotal = $subtotal + $shippingCost;

        DB::beginTransaction();

        try {
            // Validasi & kurangi stok
            foreach ($order->items as $item) {
                $product = $item->product;
                if ($product->stock < $item->qty) {
                    throw new \Exception('Stok "' . $product->product_name . '" tidak mencukupi saat checkout.');
                }
                $product->decrement('stock', $item->qty);
            }

            // Perbarui order
            $order->gross_amount = round($grandTotal, 2);
            $order->status = 'pending';
            $order->save();

            // Buat shipping
            Shipping::create([
                'order_id'        => $order->id,
                'address'         => $customer->address,
                'shipping_cost'   => round($shippingCost, 2),
                'shipping_status' => 'pending',
            ]);

            // Buat payment
            $payment = Payment::create([
                'order_id'       => $order->id,
                'amount'         => round($grandTotal, 2),
                'payment_status' => 'pending',
                'snap_token'     => null,
            ]);

            // Midtrans payload dari OrderItem
            $items_for_midtrans = [];
            foreach ($order->items as $item) {
                $items_for_midtrans[] = [
                    'id'       => (string) $item->product_id,
                    'price'    => (int) round($item->price),
                    'quantity' => (int) $item->qty,
                    'name'     => $item->product->product_name ?? $item->product->name,
                ];
            }
            $items_for_midtrans[] = [
                'id'       => 'SHIPPING',
                'price'    => (int) round($shippingCost),
                'quantity' => 1,
                'name'     => 'Ongkos Kirim',
            ];

            $snapToken = Snap::getSnapToken([
                'transaction_details' => [
                    'order_id'     => $order->invoice_number,
                    'gross_amount' => (int) round($grandTotal),
                ],
                'item_details' => $items_for_midtrans,
                'customer_details' => [
                    'first_name' => $customer->fullname ?? $user->name,
                    'email'      => $user->email,
                    'phone'      => $customer->phone,
                    'shipping_address' => ['address' => $customer->address],
                ],
            ]);

            $payment->snap_token = $snapToken;
            $payment->save();

            foreach ($order->items as $item) {
                $product = $item->product;
                if ($product->stock < $item->qty) {
                    throw new \Exception('Stok "' . $product->product_name . '" tidak mencukupi saat checkout.');
                }
                $product->decrement('stock', $item->qty);
            }

            DB::commit();

            return response()->json([
                'success'    => true,
                'snap_token' => $snapToken,
                'invoice'    => $order->invoice_number,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout final error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menyelesaikan checkout.'], 500);
        }
    }

    /**
     * Hitung harga satuan setelah diskon (asumsi: discount.value = persen).
     */
    protected function computeProductUnitPrice($product)
    {
        $basePrice = (float) ($product->price ?? 0);
        if ($product->discount && $product->discount->value > 0) {
            $percent = (float) $product->discount->value;
            $discountAmount = $basePrice * ($percent / 100);
            return round($basePrice - $discountAmount, 2);
        }
        return round($basePrice, 2);
    }

    /**
     * Hitung ringkasan dari koleksi cart (untuk estimasi di halaman checkout).
     */
    protected function calculateCartSummary($cart)
    {
        $totalItemsPrice = 0;
        $totalWeightKg = 0;

        foreach ($cart as $ci) {
            $product = $ci->product;
            $qty = (int) $ci->quantity;
            $unitPrice = $this->computeProductUnitPrice($product);
            $totalItemsPrice += $unitPrice * $qty;
            $totalWeightKg += ((float) ($product->weight ?? 0) * $qty) / 1000;
        }

        return [
            'total_items_price' => round($totalItemsPrice, 2),
            'total_weight_kg'   => round($totalWeightKg, 3),
        ];
    }

    /**
     * Webhook dari Midtrans (implementasi terpisah).
     */
    public function midtransNotification(Request $request)
    {
        try {
            // 1. Buat instance Notification dari payload POST
            $notification = new Notification();

            // 2. Ambil data dari notifikasi
            $invoice = $notification->order_id;
            $statusCode = $notification->status_code;
            $fraudStatus = $notification->fraud_status;
            $transactionStatus = $notification->transaction_status; // 'capture', 'settlement', dll.

            Log::info('Midtrans Notification Received', [
                'invoice' => $invoice,
                'status_code' => $statusCode,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
            ]);

            // 3. Cari order
            $order = Order::where('invoice_number', $invoice)->first();
            if (!$order) {
                Log::warning('Midtrans: Order not found', ['invoice' => $invoice]);
                return response()->json(['error' => 'Order not found'], 404);
            }

            $payment = $order->payment;
            if (!$payment) {
                Log::warning('Midtrans: Payment not found', ['invoice' => $invoice]);
                return response()->json(['error' => 'Payment not found'], 404);
            }

            // 4. Tentukan status berdasarkan kombinasi statusCode & transaction_status
            $paymentStatus = 'pending';
            $orderStatus = 'pending';

            if ($statusCode == 200) {
                // Transaksi sukses (misal: credit card)
                $paymentStatus = 'paid';
                $orderStatus = 'paid';
            } elseif ($statusCode == 201) {
                // Menunggu pembayaran (bank transfer)
                $paymentStatus = 'pending';
                $orderStatus = 'pending';
            } elseif ($statusCode == 202) {
                // Dibatalkan/diexpire
                $paymentStatus = 'expire';
                $orderStatus = 'cancelled';
            } elseif ($fraudStatus == 'challenge') {
                $paymentStatus = 'challenge';
                $orderStatus = 'pending';
            } elseif ($fraudStatus == 'deny') {
                $paymentStatus = 'deny';
                $orderStatus = 'cancelled';
            } elseif (in_array($transactionStatus, ['capture', 'settlement'])) {
                $paymentStatus = 'paid';
                $orderStatus = 'paid';
            }

            // 5. Simpan perubahan
            $payment->payment_status = $paymentStatus;
            $payment->save();

            $order->status = $orderStatus;
            $order->save();

            // 6. Respons sukses ke Midtrans (wajib!)
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return response()->json(['error' => 'Internal error'], 500);
        }
    }
}
