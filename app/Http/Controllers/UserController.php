<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // 1. Tampilkan Daftar User
    public function index()
    {
        // Kita exclude (jangan tampilkan) akun sendiri biar gak sengaja kehapus
        // atau tampilkan semua juga boleh. Disini saya tampilkan semua.
        $users = User::latest()->get();
        return view('admin.users.index', compact('users'));
    }

    // 2. Form Tambah
    public function create()
    {
        return view('admin.users.create');
    }

    // 3. Simpan User Baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required',
            'avatar' => 'nullable|image|max:2048'
        ]);

        // Enkripsi password sebelum disimpan
        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        User::create($data);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }

    // 4. Form Edit
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // 5. Update User
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'role' => 'required',
            'avatar' => 'nullable|image|max:2048'
        ]);

        $data = $request->except(['password', 'avatar']);

        // Cek apakah password diisi? Kalau iya, update password baru.
        // Kalau kosong, berarti password lama tetap dipakai.
        if($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Data user diperbarui!');
    }

    // 6. Hapus User
    public function destroy(User $user)
    {
        // Cegah hapus diri sendiri (sedang login)
        if(auth()->id() == $user->id) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri!');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User dihapus!');
    }
}