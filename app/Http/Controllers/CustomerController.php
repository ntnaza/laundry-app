<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // Daftar Pelanggan
    public function index()
    {
        $customers = Customer::latest()->get();
        return view('admin.customers.index', compact('customers'));
    }

    // Form Tambah
    public function create()
    {
        return view('admin.customers.create');
    }

    // Simpan Data
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|numeric|unique:customers,phone', // No HP gak boleh kembar
            'address' => 'nullable'
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil ditambahkan!');
    }

    // Lihat Profil & Riwayat Transaksi (Fitur Penting!)
    public function show(Customer $customer)
    {
        // Ambil transaksi milik orang ini, urutkan dari yg terbaru
        $history = $customer->transactions()->latest()->get();
        
        return view('admin.customers.show', compact('customer', 'history'));
    }

    // Form Edit
    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    // Update Data
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|numeric|unique:customers,phone,'.$customer->id, // Pengecualian unik buat diri sendiri
            'address' => 'nullable'
        ]);

        $customer->update($request->all());

        return redirect()->route('customers.index')->with('success', 'Data pelanggan diupdate!');
    }

    // Hapus Data
    public function destroy(Customer $customer)
    {
        // Cek dulu, kalau dia pernah transaksi, jangan dihapus sembarangan (biar data keuangan aman)
        if($customer->transactions()->count() > 0) {
            return back()->with('error', 'Gagal hapus! Pelanggan ini memiliki riwayat transaksi.');
        }

        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Pelanggan dihapus!');
    }
}