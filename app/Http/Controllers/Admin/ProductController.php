<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Discount;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brand = Brand::all();
        $categories = Category::all();
        $products = Product::latest()->paginate(5);
        $discounts = Discount::all();
        return view('admin.product', compact('products','categories','brand','discounts'));
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
            'product_name' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'brand_id' => 'required|integer',
            'discount_id' => 'nullable|integer',
            'description' => 'required|string',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric',
            'weight' => 'required|numeric',
            'picture' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Upload gambar ke public/products
        $filename = time() . '-' . $request->file('picture')->getClientOriginalName();
        $request->file('picture')->move(public_path('products'), $filename);

        Product::create([
            'product_name' => $request->product_name,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'discount_id' => $request->discount_id,
            'description' => $request->description,
            'stock' => $request->stock,
            'price' => $request->price,
            'weight' => $request->weight,
            'picture' => $filename,
        ]);

        return back()->with('success', 'Produk berhasil ditambahkan.');
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
    public function update(Request $request, $id)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'brand_id' => 'required|integer',
            'discount_id' => 'nullable|integer',
            'description' => 'required|string',
            'stock' => 'nullable|integer|min:0',
            'price' => 'required|numeric',
            'weight' => 'required|numeric',
            'picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $product = Product::findOrFail($id);
        $filename = $product->picture;

        // Jika upload gambar baru
        if ($request->hasFile('picture')) {
            // Hapus gambar lama
            if (file_exists(public_path('products/' . $filename))) {
                unlink(public_path('products/' . $filename));
            }

            // Upload baru
            $filename = time() . '-' . $request->file('picture')->getClientOriginalName();
            $request->file('picture')->move(public_path('products'), $filename);
        }

        $product->update([
            'product_name' => $request->product_name,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'discount_id' => $request->discount_id,
            'description' => $request->description,
            'price' => $request->price,
            'weight' => $request->weight,
            'picture' => $filename,
        ]);

        return back()->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Hapus gambar
        if ($product->picture && file_exists(public_path('products/' . $product->picture))) {
            unlink(public_path('products/' . $product->picture));
        }

        $product->delete();

        return back()->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Update STOK saja (fitur khusus).
     */
    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'stock' => 'required|integer|min:0'
        ]);

        $product = Product::findOrFail($id);

        $product->update([
            'stock' => $request->stock
        ]);

        return back()->with('success', 'Stok berhasil diperbarui.');
    }
}
