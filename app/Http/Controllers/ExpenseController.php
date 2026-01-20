<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan ini biar aman

class ExpenseController extends Controller
{
    public function index()
    {
        // Kita balikin 'with(user)' biar nama pelapornya muncul lagi di tabel
        $expenses = Expense::with('user')->latest()->get();
        return view('admin.expenses.index', compact('expenses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'amount' => 'required|numeric',
            'date' => 'required|date',
        ]);

        // AMBIL SEMUA INPUTAN
        $data = $request->all();
        
        // TAMBAHKAN ID USER YANG SEDANG LOGIN (Wajib diisi biar gak error 1364)
        $data['user_id'] = Auth::id(); 

        Expense::create($data);

        return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil dicatat!');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Data dihapus!');
    }
}