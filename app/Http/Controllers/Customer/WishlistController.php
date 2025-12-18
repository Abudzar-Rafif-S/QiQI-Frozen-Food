<?php

namespace App\Http\Controllers\Customer;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customerId = Auth::user()->customer->id;
        $wishlists = Wishlist::where('customer_id', $customerId)
            ->with('product')
            ->get();

        return view('customer.wishlist.index', compact('wishlists'));
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
        ]);

        $customerId = Auth::user()->customer->id;

        $wishlist = Wishlist::firstOrCreate([
            'customer_id' => $customerId,
            'product_id' => $request->product_id
        ]);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke wishlist!');
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wishlist $wishlist)
    {
        $customerId = Auth::user()->customer->id;
        if ($wishlist->customer_id != $customerId) {
            abort(403);
        }

        $wishlist->delete();
        return redirect()->back()->with('success', 'Produk berhasil dihapus dari wishlist!');
    }
}
