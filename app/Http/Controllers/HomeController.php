<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Setting;
use App\Models\Service; // <--- INI WAJIB DITAMBAH

class HomeController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        
        // Ambil semua paket laundry buat dipajang di depan
        $services = Service::all(); 

        // Kirim $setting DAN $services ke tampilan
        return view('landing', compact('setting', 'services'));
    }

    public function track(Request $request)
    {
        $request->validate(['invoice_code' => 'required']);

        $transaction = Transaction::where('invoice_code', $request->invoice_code)
                        ->with('details.service', 'logs')
                        ->first();

        if(!$transaction) {
            return redirect()->back()->with('error', 'Nomor invoice tidak ditemukan!');
        }

        return view('tracking', compact('transaction'));
    }
}