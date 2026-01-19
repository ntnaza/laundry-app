<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with('user')->latest()->get();
        return view('admin.expenses.index', compact('expenses'));
    }

    public function create()
    {
        return view('admin.expenses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'amount' => 'required|numeric',
            'date' => 'required|date',
        ]);

        Expense::create([
            'description' => $request->description,
            'amount' => $request->amount,
            'date' => $request->date,
            'note' => $request->note,
            'user_id' => Auth::id() ?? 1 // Fallback ke admin kalau belum login
        ]);

        return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil dicatat!');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Data dihapus!');
    }
}