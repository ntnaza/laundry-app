<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Service; // <--- JANGAN LUPA PANGGIL MODEL SERVICE

class HomeController extends Controller
{
    // Halaman Depan
    public function index()
    {
        // Ambil data layanan buat ditampilkan di landing page
        $services = Service::all(); 
        
        // Return view 'welcome' (bukan landing.blade.php kalau pakai nama default)
        return view('welcome', compact('services'));
    }

    // Fungsi Cek Resi
    public function track(Request $request)
    {
        // 1. Ambil Data Layanan lagi (biar pas reload, daftar harga gak hilang)
        $services = Service::all();

        // 2. Cari Transaksi
        $tracking_result = Transaction::with('customer')
                            ->where('invoice_code', $request->invoice_code)
                            ->first();

        // 3. Jika Tidak Ketemu
        if (!$tracking_result) {
            return redirect()->route('home')->with('error', 'Kode Invoice tidak ditemukan! Cek lagi ya.');
        }

        // 4. Jika Ketemu, balik ke halaman depan bawa data hasil + data layanan
        return view('welcome', compact('tracking_result', 'services'));
    }
}