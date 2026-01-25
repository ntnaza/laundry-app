<?php

namespace App\Http\Controllers;

use App\Models\Service;
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
        return view('admin.services.create');
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
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        Service::create($data);

        return redirect()->route('services.index')->with('success', 'Paket berhasil ditambahkan!');
    }

    // Tampilkan Form Edit
    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
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