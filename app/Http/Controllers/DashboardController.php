<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Expense;
use App\Models\Customer;
use App\Models\TransactionDetail; // Buat hitung total baju
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Hitung Pemasukan (Status harus 'paid' biar valid)
        $pemasukan = Transaction::where('payment_status', 'paid')->sum('total_price');

        // 2. Hitung Pengeluaran
        $pengeluaran = Expense::sum('amount');

        // 3. Hitung Profit
        $profit = $pemasukan - $pengeluaran;

        // 4. Data Pendukung Lain
        $total_transaksi = Transaction::count();
        $total_customer = Customer::count();

        return view('admin.dashboard', compact('pemasukan', 'pengeluaran', 'profit', 'total_transaksi', 'total_customer'));
    }
}