<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Service;
use App\Models\Customer;
use App\Models\Testimonial;
use App\Models\Promo;

class HomeController extends Controller
{
    // Halaman Depan
    public function index()
    {
        // 1. Data Layanan
        $services = Service::all(); 

        // 2. Statistik
        $totalCustomers = Customer::count();
        $avgRating = Testimonial::avg('rate') ?? 0;
        
        // 3. Testimoni (Ambil 3 terbaru)
        $reviews = Testimonial::with('user')->latest()->take(3)->get();

        // 4. Promo Terbaik (Untuk Banner)
        $bestPromo = Promo::where('is_active', true)
                        ->whereDate('end_date', '>=', now())
                        ->orWhereNull('end_date') // Promo selamanya
                        ->orderBy('value', 'desc')
                        ->first();
        
        return view('welcome', compact('services', 'totalCustomers', 'avgRating', 'reviews', 'bestPromo'));
    }

    // Fungsi Cek Resi
    public function track(Request $request)
    {
        // Tetap bawa data landing page lainnya biar gak error
        $services = Service::all();
        $totalCustomers = Customer::count();
        $avgRating = Testimonial::avg('rate') ?? 0;
        $reviews = Testimonial::with('user')->latest()->take(3)->get();
        $bestPromo = Promo::where('is_active', true)->orderBy('value', 'desc')->first();

        // Cari Transaksi
        $tracking_result = Transaction::with('customer')
                            ->where('invoice_code', $request->invoice_code)
                            ->first();

        if (!$tracking_result) {
            return redirect()->route('home')->with('error', 'Kode Invoice tidak ditemukan! Cek lagi ya.');
        }

        return view('welcome', compact('tracking_result', 'services', 'totalCustomers', 'avgRating', 'reviews', 'bestPromo'));
    }
}