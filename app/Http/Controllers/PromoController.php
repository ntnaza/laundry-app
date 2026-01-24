<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promos = Promo::latest()->get();
        return view('admin.promos.index', compact('promos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.promos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Otomatis besarkan huruf sebelum divalidasi
        $request->merge(['code' => strtoupper($request->code)]);

        $request->validate([
            'code' => 'required|unique:promos,code|alpha_num|uppercase',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_spend' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        Promo::create($request->all());

        return redirect()->route('promos.index')->with('success', 'Kode Promo berhasil dibuat!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Promo $promo)
    {
        return view('admin.promos.edit', compact('promo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Promo $promo)
    {
        // Otomatis besarkan huruf sebelum divalidasi
        $request->merge(['code' => strtoupper($request->code)]);

        $request->validate([
            'code' => 'required|alpha_num|uppercase|unique:promos,code,' . $promo->id,
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_spend' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Handle checkbox is_active (karena kalau unchecked, dia gak kirim value)
        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        $promo->update($data);

        return redirect()->route('promos.index')->with('success', 'Kode Promo berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Promo $promo)
    {
        $promo->delete();
        return redirect()->route('promos.index')->with('success', 'Kode Promo dihapus!');
    }
}