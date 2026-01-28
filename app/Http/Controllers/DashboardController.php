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
        // Statistik Utama
        $totalTransactions = Transaction::count();
        
        // Income Today
        $todayIncome = Transaction::whereDate('created_at', Carbon::today())->sum('total_price');
        
        // Income Month (Optional, kept for future use)
        $incomeMonth = Transaction::whereMonth('created_at', Carbon::now()->month)->sum('total_price');
        
        // Total Income (Accumulated)
        $totalIncome = Transaction::sum('total_price');

        // Total Customers
        $totalCustomers = Customer::count();
        
        // Transaksi Terbaru (Latest 5)
        $latestTransactions = Transaction::with('customer')->latest()->take(5)->get();

        // Chart Data (7 Hari Terakhir)
        $chartData = [];
        $chartCategories = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dailyIncome = Transaction::whereDate('created_at', $date)->sum('total_price');
            
            $chartData[] = $dailyIncome;
            $chartCategories[] = $date->format('d M');
        }

        // Pass current server time for reliable polling
        $serverTime = now()->toDateTimeString();

        return view('admin.dashboard', compact(
            'totalTransactions', 
            'todayIncome', 
            'incomeMonth', 
            'totalIncome', 
            'totalCustomers',
            'latestTransactions', 
            'chartData',
            'chartCategories',
            'serverTime'
        ));
    }

    public function checkNewOrders(Request $request)
    {
        // Gunakan timestamp untuk cek data baru (lebih akurat daripada ID)
        $lastCheck = $request->query('last_check');
        
        if (!$lastCheck) {
            return response()->json(['has_new' => false]);
        }
        
        // REVISI LOGIC: HANYA NOTIFIKASI JIKA FASE 2 SELESAI (Sudah ada item/Subtotal > 0)
        // Kita abaikan 'draft' kosong atau yang baru bayar DP doang.
        $newOrder = Transaction::with('customer')
                        ->where('updated_at', '>', $lastCheck)
                        ->where('subtotal', '>', 0) // <--- KUNCI: Hanya order yang sudah ada isinya
                        ->whereIn('status', ['pending', 'process']) // Pastikan statusnya sudah resmi (bukan draft awal, atau draft yg belum diproses)
                        ->latest('updated_at')
                        ->first();

        if ($newOrder) {
            return response()->json([
                'has_new' => true,
                'last_check' => now()->toDateTimeString(), // Update waktu cek
                'invoice' => $newOrder->invoice_code,
                'customer_name' => $newOrder->customer->name ?? 'Pelanggan Baru',
                'total' => number_format($newOrder->total_price, 0, ',', '.'),
                'status' => strtoupper($newOrder->status)
            ]);
        }

        return response()->json(['has_new' => false]);
    }
}
