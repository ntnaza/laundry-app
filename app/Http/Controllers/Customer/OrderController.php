<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Transaction; // Jangan lupa panggil Model Transaction
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        // Ambil riwayat cucian milik user yang sedang login saja
        $myOrders = Transaction::where('customer_id', Auth::id())
                        ->latest()
                        ->get();

        return view('customer.dashboard', compact('myOrders'));
    }

    public function create()
    {
        return view('customer.order_create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required', // Validasi No HP
            'pickup_address' => 'required|string|max:255',
            'delivery_type' => 'required',
        ]);

        // --- LOGIKA BARU: AUTO CREATE CUSTOMER ---
        // Cari apakah User ini sudah terdaftar sebagai Customer (berdasarkan Nama)?
        // (Idealnya pakai user_id, tapi karena struktur DB lama belum ada user_id di tabel customers, kita pakai Nama dulu sebagai penghubung)
        
        $customer = \App\Models\Customer::firstOrCreate(
            ['name' => Auth::user()->name], // Cari berdasarkan Nama User yg login
            [
                // Kalau belum ada, isi data baru ini:
                'phone' => $request->phone,
                'address' => $request->pickup_address
            ]
        );
        // -----------------------------------------

        // Bikin Kode Invoice Unik
        $invoice = 'TRX-' . mt_rand(10000, 99999);

        Transaction::create([
            'invoice_code' => $invoice,
            'customer_id' => $customer->id, // <--- PAKAI ID DARI TABEL CUSTOMER (BUKAN AUTH ID)
            'user_id' => null, // Kasir kosong dulu
            'total_price' => 0,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'pickup_address' => $request->pickup_address,
            'delivery_type' => $request->delivery_type,
            'delivery_status' => 'pending',
            'note' => $request->note
        ]);

        return redirect()->route('customer.dashboard')->with('success', 'Kurir sedang dipanggil! Mohon tunggu.');
    }
}