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
            'description' => 'required|string|max:255',
            'amount'      => 'required|numeric|min:0',
            'date'        => 'required|date',
        ]);

        Expense::create([
            'description' => $request->description,
            'amount'      => $request->amount,
            'date'        => $request->date,
            'user_id'     => Auth::id() // Otomatis catat siapa yang input
        ]);

        return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil dicatat!');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Data dihapus!');
    }
}