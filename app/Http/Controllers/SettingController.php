<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        // Ambil data toko (ID 1)
        $setting = Setting::first();
        return view('admin.settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'shop_name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'delivery_rate_per_km' => 'nullable|numeric',
            'logo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048' 
        ]);

        $setting = Setting::first();
        $data = $request->except('logo');

        // Logika Upload Logo
        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada (opsional, skip dulu biar simpel)

            // Simpan logo baru ke folder public/storage/logo
            $path = $request->file('logo')->store('logo', 'public');
            $data['logo'] = $path;
        }

        $setting->update($data);

        return redirect()->back()->with('success', 'Pengaturan Toko diupdate!');
    }
}