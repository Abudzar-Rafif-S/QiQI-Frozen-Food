<?php

namespace App\Http\Controllers\Admin;

use App\Models\ShippingRate;
use App\Models\City;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShippingRateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shippingRates = ShippingRate::with('city')->get();
        $cities = City::orderBy('city_name')->get();

        return view('admin.masterdata.shippingrates', compact('shippingRates', 'cities'));
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
        $validated = $request->validate([
            'city_id'      => 'required|exists:cities,id',
            'price_per_kg' => 'required|numeric|min:0',
            'note'         => 'nullable|string|max:255',
        ]);

        $rate = ShippingRate::create($validated);

        // Jika modal pakai AJAX → return JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data'    => $rate->load('city')
            ]);
        }

        return redirect()->route('admin.shipping-rates.index')
            ->with('success', 'Shipping rate berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

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
    public function update(Request $request, ShippingRate $shippingRate)
    {
        $validated = $request->validate([
            'city_id'      => 'required|exists:cities,id',
            'price_per_kg' => 'required|numeric|min:0',
            'note'         => 'nullable|string|max:255',
        ]);

        $shippingRate->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data'    => $shippingRate->load('city')
            ]);
        }

        return redirect()->route('admin.shipping-rates.index')
            ->with('success', 'Shipping rate berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, ShippingRate $shippingRate)
    {
        $shippingRate->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.shipping-rates.index')
            ->with('success', 'Shipping rate berhasil dihapus.');
    }
}
