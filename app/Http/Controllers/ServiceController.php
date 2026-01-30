<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Inventory;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    // Tampilkan Daftar Paket
    public function index()
    {
        $services = Service::all();
        return view('admin.services.index', compact('services'));
    }

    // Tampilkan Form Tambah
    public function create()
    {
        $inventories = Inventory::all();
        return view('admin.services.create', compact('inventories'));
    }

    // Simpan Data ke Database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'type' => 'required',
            'unit' => 'required',
            'estimate_duration' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'materials' => 'nullable|array',
            'materials.*.inventory_id' => 'required|exists:inventories,id',
            'materials.*.quantity' => 'required|numeric|min:0',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        $service = Service::create($data);

        // Simpan Resep Bahan Baku
        if ($request->has('materials')) {
            foreach ($request->materials as $material) {
                if (isset($material['inventory_id']) && isset($material['quantity']) && $material['quantity'] > 0) {
                    $service->materials()->attach($material['inventory_id'], ['quantity' => $material['quantity']]);
                }
            }
        }

        return redirect()->route('services.index')->with('success', 'Paket berhasil ditambahkan!');
    }

    // Tampilkan Form Edit
    public function edit(Service $service)
    {
        $inventories = Inventory::all();
        $service->load('materials'); 
        return view('admin.services.edit', compact('service', 'inventories'));
    }

    // Update Data
    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'type' => 'required',
            'unit' => 'required',
            'estimate_duration' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'materials' => 'nullable|array',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($service->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($service->image)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($service->image);
            }
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        $service->update($data);

        // Update Resep Bahan Baku
        $materialsData = [];
        if ($request->has('materials')) {
            foreach ($request->materials as $material) {
                if (isset($material['inventory_id']) && isset($material['quantity']) && $material['quantity'] > 0) {
                    // Gunakan ID Inventory sebagai key untuk sync
                    $materialsData[$material['inventory_id']] = ['quantity' => $material['quantity']];
                }
            }
        }
        $service->materials()->sync($materialsData);

        return redirect()->route('services.index')->with('success', 'Paket berhasil diupdate!');
    }

    // Hapus Data
    public function destroy(Service $service)
    {
        // Hapus gambar jika ada
        if ($service->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($service->image)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($service->image);
        }
        
        $service->delete();
        return redirect()->route('services.index')->with('success', 'Paket dihapus!');
    }
}