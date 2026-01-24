<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Expense; // Import Expense
use Illuminate\Http\Request;
use App\Exports\TransactionExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Default tanggal: Awal bulan ini sampai hari ini
        $startDate = $request->start_date ?? date('Y-m-01');
        $endDate = $request->end_date ?? date('Y-m-d');

        // Ambil data transaksi yang SUDAH LUNAS (paid) dalam rentang tanggal
        $transactions = Transaction::with('customer')
                        ->where('payment_status', 'paid')
                        ->whereDate('created_at', '>=', $startDate)
                        ->whereDate('created_at', '<=', $endDate)
                        ->latest()
                        ->get();

        // Hitung total omzet di rentang tanggal tsb
        $totalOmzet = $transactions->sum('total_price');

        return view('admin.reports.index', compact('transactions', 'totalOmzet', 'startDate', 'endDate'));
    }

    public function profit(Request $request)
    {
        $startDate = $request->start_date ?? date('Y-m-01');
        $endDate = $request->end_date ?? date('Y-m-d');

        // 1. Hitung Pemasukan (Omset)
        $income = Transaction::where('payment_status', 'paid')
                    ->whereDate('created_at', '>=', $startDate)
                    ->whereDate('created_at', '<=', $endDate)
                    ->sum('total_price');

        // 2. Hitung Pengeluaran
        $expense = Expense::whereDate('date', '>=', $startDate)
                    ->whereDate('date', '<=', $endDate)
                    ->sum('amount');

        // 3. Hitung Laba Bersih
        $netProfit = $income - $expense;

        return view('admin.reports.profit', compact('income', 'expense', 'netProfit', 'startDate', 'endDate'));
    }

    public function exportExcel()
    {
    return Excel::download(new TransactionExport, 'laporan-transaksi.xlsx');
    }
}