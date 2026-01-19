<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Transaction;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Halaman Depan
    public function index()
    {
        // Ambil data paket buat dipajang harganya
        $services = Service::all();
        return view('landing', compact('services'));
    }

    // Fitur Cek Resi (Tracking)
    public function track(Request $request)
    {
        $invoice = $request->invoice_code;
        
        // Cari transaksi berdasarkan kode invoice
        $transaction = Transaction::with('customer', 'details.service')
                        ->where('invoice_code', $invoice)
                        ->first();

        if (!$transaction) {
            return redirect('/')->with('error', 'Nomor Invoice tidak ditemukan!');
        }

        // Kalau ketemu, kirim datanya balik ke halaman depan
        return view('landing', [
            'services' => Service::all(),
            'tracking_result' => $transaction
        ]);
    }
}