<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; // Import Storage

class ProfileController extends Controller
{
    public function index()
    {
        return view('admin.profile.index', [
            'user' => Auth::user()
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|min:6|confirmed', 
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi Foto
            
            // Validasi Data Pelanggan (Jika User adalah Customer)
            'phone' => 'nullable|numeric',
            'address' => 'nullable|string',
        ]);

        // 1. Update Data Dasar User
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // 2. Handle Upload Foto
        if ($request->hasFile('avatar')) {
            // Hapus foto lama jika ada (dan bukan placeholder default jika kita pakai)
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            // Simpan foto baru
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        // 3. Update Data Detail Pelanggan (HANYA JIKA ROLE CUSTOMER)
        if ($user->role == 'customer') {
            
            // Cek apakah data pelanggan sudah ada?
            $customer = \App\Models\Customer::where('user_id', $user->id)->first();
            
            if ($customer) {
                // Cek konflik nomor HP
                if ($request->phone && $request->phone != $customer->phone) {
                    $exists = \App\Models\Customer::where('phone', $request->phone)->where('id', '!=', $customer->id)->exists();
                    if ($exists) return back()->withErrors(['phone' => 'Nomor WhatsApp sudah digunakan oleh akun lain.']);
                }

                $customer->update([
                    'name' => $user->name,
                    'phone' => $request->phone ?? $customer->phone,
                    'address' => $request->address ?? $customer->address,
                    'latitude' => $request->latitude ?? $customer->latitude,
                    'longitude' => $request->longitude ?? $customer->longitude,
                ]);
            } else {
                // Jika belum punya data customer, buat baru
                if ($request->phone) {
                    // Cek duplikat phone global
                    $exists = \App\Models\Customer::where('phone', $request->phone)->exists();
                    if ($exists) {
                        // Coba klaim data lama yang user_id nya null
                        $orphanCustomer = \App\Models\Customer::where('phone', $request->phone)->whereNull('user_id')->first();
                        if ($orphanCustomer) {
                            $orphanCustomer->update(['user_id' => $user->id, 'name' => $user->name]);
                        } else {
                            return back()->withErrors(['phone' => 'Nomor WhatsApp sudah terdaftar.']);
                        }
                    } else {
                        \App\Models\Customer::create([
                            'user_id' => $user->id,
                            'name' => $user->name,
                            'phone' => $request->phone,
                            'address' => $request->address ?? '-',
                            'latitude' => $request->latitude,
                            'longitude' => $request->longitude
                        ]);
                    }
                }
            }
        }

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}