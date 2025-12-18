<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::all();
        return view('admin.masterdata.brand', compact('brands'));
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
            'name' => 'required|unique:brands,name',
        ]);

        Brand::create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Brand berhasil ditambahkan.');
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
    public function update(Request $request, Brand $brand)
    {
         $request->validate([
            'name' => "required|unique:brands,name,{$brand->id}",
        ]);

        $brand->update([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Brand berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        $brand->delete();

        return redirect()->back()->with('success', 'Brand berhasil dihapus.');
    }
}
