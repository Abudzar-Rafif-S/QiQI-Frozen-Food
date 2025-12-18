<?php

namespace App\Http\Controllers\Customer;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

    public function dashboard()
    {
        // Produk terbaru (8 produk)
        $latestProducts = Product::latest()->take(8)->get();

        // Produk yang sedang diskon
        $discountedProducts = Product::whereNotNull('discount_id')
            ->with('discount')
            ->take(8)
            ->get();

        // Kategori yang ingin ditampilkan di landing page
        $categories = Category::take(6)->get();

        // Brand populer (opsional)
        $brands = Brand::take(6)->get();
        return view('customer.dashboard', compact('latestProducts', 'discountedProducts', 'categories', 'brands'));
    }

    public function products()
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(12);

        return view('customer.products', compact('products'));
    }

    public function productDetail($id)
    {
        $product = Product::with(['category', 'brand', 'discount', 'reviews.customer'])->findOrFail($id);

        return view('customer.product-detail', compact('product'));
    }

    public function productsByCategory($categoryId)
    {
        $category = Category::findOrFail($categoryId);

        $products = Product::where('category_id', $categoryId)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('customer.products-by-category', compact('category', 'products'));
    }


    public function search(Request $request)
    {
        $keyword = trim($request->q);

        // Jika search kosong, redirect atau tampilkan semua produk
        if (!$keyword) {
            return redirect()->route('customer.products');
        }

        $products = Product::query()
            ->where('product_name', 'LIKE', "%{$keyword}%")

            // Search brand
            ->orWhereHas('brand', function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%");
            })

            // Search category
            ->orWhereHas('category', function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%");
            })

            ->paginate(12)
            ->withQueryString();

        return view('customer.search-result', compact('products', 'keyword'));
    }
}
