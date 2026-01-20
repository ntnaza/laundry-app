<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Customer;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // Jangan lupa import Auth

class TransactionController extends Controller
{
    // Tampilkan Riwayat Transaksi
    public function index()
    {
        // Ambil data transaksi terbaru dengan relasi customer & user
        $transactions = Transaction::with(['customer', 'user'])->latest()->get();
        return view('admin.transactions.index', compact('transactions'));
    }

    // Form Transaksi Baru
    public function create()
    {
        $customers = Customer::all(); // Buat dropdown pelanggan
        $services = Service::all();   // Buat dropdown paket
        
        // Generate No Invoice Otomatis (INV-TAHUNBULANTANGGAL-JAMMENIT)
        $invoice_code = 'INV-' . date('Ymd-Hi'); 

        return view('admin.transactions.create', compact('customers', 'services', 'invoice_code'));
    }

    // PROSES SIMPAN (The Magic Happens Here)
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'customer_id' => 'required',
            'service_id' => 'required|array', // Harus array karena itemnya banyak
            'qty' => 'required|array',
        ]);

        // Gunakan DB Transaction biar kalau error di tengah, data ga masuk setengah-setengah
        DB::transaction(function () use ($request) {
            
            // 1. Simpan Kepala Transaksi (Transaction)
            $transaction = Transaction::create([
                'invoice_code' => $request->invoice_code,
                'customer_id' => $request->customer_id,
                'user_id' => Auth::id() ?? 1, // Mengambil ID admin yang login
                'total_price' => 0, // Nanti diupdate setelah hitung detail
                'status' => 'pending',
                'payment_status' => 'unpaid'
            ]);

            $total_bayar = 0;

            // 2. Looping Item Cucian (Transaction Details)
            // Kita loop berdasarkan jumlah layanan yang dipilih
            foreach ($request->service_id as $key => $service_id) {
                
                $service = Service::find($service_id);
                $qty = $request->qty[$key];
                $subtotal = $service->price * $qty;

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'service_id' => $service_id,
                    'qty' => $qty,
                    'price_per_unit' => $service->price,
                    'subtotal' => $subtotal
                ]);

                $total_bayar += $subtotal;
            }

            // 3. Update Total Harga di Tabel Utama
            $transaction->update(['total_price' => $total_bayar]);
        });

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dibuat!');
    }


    public function show(Transaction $transaction)
    {
        // Kita load detail item dan servicenya biar muncul namanya
        $transaction->load(['details.service', 'customer']);
        
        return view('admin.transactions.show', compact('transaction'));
    }

    public function updateStatus(Request $request, Transaction $transaction)
    {
        $request->validate([
            'status' => 'required',
            'payment_status' => 'required'
        ]);

        // 1. Update Tabel Transaksi
        $transaction->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status
        ]);

        // 2. Catat di Log (Biar ketahuan siapa yang ubah)
        // Pastikan Model TransactionLog sudah dibuat ya (di tahap awal)
        \App\Models\TransactionLog::create([
            'transaction_id' => $transaction->id,
            'status' => $request->status,
            'user_id' => Auth::id() // Siapa yang klik
        ]);

        return redirect()->back()->with('success', 'Status cucian berhasil diperbarui!');
    }

    public function printThermal(Transaction $transaction)
    {
        // Ambil data setting toko buat header struk
        $setting = \App\Models\Setting::first();
        return view('admin.transactions.print_thermal', compact('transaction', 'setting'));
    }

    public function edit($id)
    {
        // Ambil data transaksi
        $transaction = Transaction::findOrFail($id);
        
        // Tampilkan halaman form edit (Timbang & Harga)
        return view('admin.transactions.edit', compact('transaction'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'total_price' => 'required|numeric',
            'status' => 'required',
            'delivery_status' => 'required' // Admin juga bisa update status kurir
        ]);

        $transaction = Transaction::findOrFail($id);

        // Update Data
        $transaction->update([
            'total_price' => $request->total_price,
            'status' => $request->status,
            'delivery_status' => $request->delivery_status,
            'user_id' => auth()->id() // Admin yang login otomatis jadi penanggung jawab (Kasir)
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui!');
    }
}