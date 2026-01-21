<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. DATA KARTU ATAS (Ringkasan)
        $totalTransactions = Transaction::count();
        $totalCustomers = Customer::count();
        $totalIncome = Transaction::where('payment_status', 'paid')->sum('total_price');
        $todayIncome = Transaction::where('payment_status', 'paid')
                        ->whereDate('created_at', Carbon::today())
                        ->sum('total_price');

        // 2. DATA GRAFIK (Pendapatan 7 Hari Terakhir)
        // Kita loop 7 hari ke belakang
        $chartData = [];
        $chartCategories = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            // Hitung omset di tanggal tersebut
            $income = Transaction::where('payment_status', 'paid')
                        ->whereDate('created_at', $date)
                        ->sum('total_price');
            
            $chartCategories[] = $date->format('d M'); // Label (Tgl)
            $chartData[] = $income; // Data (Duit)
        }

        // 3. TRANSAKSI TERBARU (5 Biji) buat tabel mini
        $latestTransactions = Transaction::with('customer')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalTransactions', 
            'totalCustomers', 
            'totalIncome', 
            'todayIncome',
            'chartData',
            'chartCategories',
            'latestTransactions'
        ));
    }
    // Fungsi buat dipanggil sama JavaScript (AJAX)
    public function checkNewOrders()
    {
        // Hitung orderan yang butuh kurir (delivery_status = pending)
        // Dan status transaksinya masih pending (belum diproses)
        $newOrders = \App\Models\Transaction::where('delivery_type', '!=', 'none')
                        ->where('delivery_status', 'pending')
                        ->where('status', 'pending')
                        ->count();

        return response()->json([
            'new_orders' => $newOrders
        ]);
    }
}