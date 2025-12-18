<?php

namespace App\Http\Controllers\Customer;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customer = Auth::user()->customer;

        // Ambil semua cart milik customer
        $carts = Cart::where('customer_id', $customer->id)
            ->with(['product'])
            ->get();

        // Jika keranjang kosong, return default
        if ($carts->isEmpty()) {
            return view('customer.cart.index', [
                'carts'        => $carts,
                'totalPrice'   => 0,
                'totalWeight'  => 0,
                'totalKg'      => 0,
                'shippingCost' => 0,
                'grandTotal'   => 0,
            ]);
        }

        // Hitung total harga dan total berat (gram)
        $totalPrice = 0;
        $totalWeight = 0;

        foreach ($carts as $item) {
            $totalPrice += $item->product->price * $item->quantity;
            $totalWeight += $item->product->weight * $item->quantity; // gram
        }

        // GRAM → KG (dibulatkan ke atas)
        $totalKg = ceil($totalWeight / 1000);

        // Ambil shipping rate berdasarkan kota customer
        $cityId = $customer->city_id;
        $shippingRate = \App\Models\ShippingRate::where('city_id', $cityId)->first();

        // Jika tidak ada shipping rate → default 0
        $shippingCost = $shippingRate
            ? $shippingRate->price_per_kg * $totalKg
            : 0;

        // GRAND TOTAL = harga produk + ongkir
        $grandTotal = $totalPrice + $shippingCost;

        return view('customer.cart.index', [
            'carts'        => $carts,
            'totalPrice'   => $totalPrice,
            'totalWeight'  => $totalWeight,
            'totalKg'      => $totalKg,
            'shippingCost' => $shippingCost,
            'grandTotal'   => $grandTotal,
            'shippingRate' => $shippingRate
        ]);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $customerId = Auth::user()->customer->id;

        $cart = Cart::updateOrCreate(
            [
                'customer_id' => $customerId,
                'product_id' => $request->product_id
            ],
            [
                'quantity' => $request->quantity
            ]
        );

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        $customerId = Auth::user()->customer->id;
        if ($cart->customer_id != $customerId) {
            abort(403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart->update([
            'quantity' => $request->quantity
        ]);

        return redirect()->back()->with('success', 'Jumlah produk berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        $customerId = Auth::user()->customer->id;
        if ($cart->customer_id != $customerId) {
            abort(403);
        }

        $cart->delete();
        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang!');
    }

    public function summary()
    {
        $customer = Auth::user()->customer;

        $carts = Cart::where('customer_id', $customer->id)
            ->with('product')
            ->get();

        if ($carts->isEmpty()) {
            return response()->json([
                'total_price'  => 0,
                'total_weight' => 0,
                'total_kg'     => 0
            ]);
        }

        $totalPrice  = 0;
        $totalWeight = 0;

        foreach ($carts as $item) {
            $totalPrice  += $item->product->price * $item->quantity;
            $totalWeight += $item->product->weight * $item->quantity; // gram
        }

        $totalKg = ceil($totalWeight / 1000);

        return response()->json([
            'total_price'  => $totalPrice,
            'total_weight' => $totalWeight,
            'total_kg'     => $totalKg
        ]);
    }
}
