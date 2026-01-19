<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CustomerController;


// 1. Jalur Publik (Landing Page)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/track', [HomeController::class, 'track'])->name('track');

// 2. Jalur Admin (Dashboard & Sistem)
// Kita kelompokkan biar rapi dengan prefix "admin"
Route::prefix('admin')->group(function () {
    
    // Dashboard (Pindah kesini)
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Menu-menu Admin lainnya
    Route::resource('services', ServiceController::class);
    Route::resource('transactions', TransactionController::class);
    Route::resource('expenses', ExpenseController::class);
    Route::put('/transactions/{transaction}/update-status', [TransactionController::class, 'updateStatus'])->name('transactions.updateStatus');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::resource('customers', CustomerController::class);

});