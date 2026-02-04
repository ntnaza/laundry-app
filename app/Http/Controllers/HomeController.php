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

        // 4. Promo Aktif (Untuk Slider)
        $activePromos = Promo::where('is_active', true)
                        ->where(function ($query) {
                            $query->whereDate('end_date', '>=', now())
                                  ->orWhereNull('end_date');
                        })
                        ->latest()
                        ->get();
        
        // 5. Pengaturan Toko (Untuk Map & Kontak)
        $setting = \App\Models\Setting::first();
        
        return view('welcome', compact('services', 'totalCustomers', 'avgRating', 'reviews', 'activePromos', 'setting'));
    }

    // Fungsi Cek Resi
    public function track(Request $request)
    {
        // Cari Transaksi
        $tracking_result = Transaction::with('customer')
                            ->where('invoice_code', $request->invoice_code)
                            ->first();

        // JIKA REQUEST VIA AJAX (Fetch/Axios)
        if ($request->ajax()) {
            if (!$tracking_result) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kode Invoice tidak ditemukan!'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'invoice' => $tracking_result->invoice_code,
                    'status' => $tracking_result->status, // pending, process, ready, done
                    'customer' => $tracking_result->customer->name,
                    'total' => number_format($tracking_result->total_price),
                    'date' => $tracking_result->created_at->format('d M Y H:i')
                ]
            ]);
        }

        // FALLBACK (Non-JS)
        $services = Service::all();
        $totalCustomers = Customer::count();
        $avgRating = Testimonial::avg('rate') ?? 0;
        $reviews = Testimonial::with('user')->latest()->take(3)->get();
        
        $activePromos = Promo::where('is_active', true)
                        ->where(function ($query) {
                            $query->whereDate('end_date', '>=', now())
                                  ->orWhereNull('end_date');
                        })
                        ->latest()
                        ->get();

        $setting = \App\Models\Setting::first();

        if (!$tracking_result) {
            return redirect()->route('home')->with('error', 'Kode Invoice tidak ditemukan! Cek lagi ya.');
        }

        return view('welcome', compact('tracking_result', 'services', 'totalCustomers', 'avgRating', 'reviews', 'activePromos', 'setting'));
    }
}