<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, $productId)
    {
        if (!Auth::check()) {
            return back()->with('error', 'Anda harus login terlebih dahulu untuk menulis ulasan.');
        }

        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $user = Auth::user();

        // Cek apakah user punya profil customer
        if (!$user->customer) {
            return back()->with('error', 'Profil customer belum dibuat.');
        }

        $customer = $user->customer;

        // Cek duplikasi review
        $existing = Review::where('product_id', $productId)
            ->where('customer_id', $customer->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah mengirim ulasan untuk produk ini.');
        }

        Review::create([
            'product_id' => $productId,
            'customer_id' => $customer->id,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Ulasan berhasil dikirim.');
    }
}
