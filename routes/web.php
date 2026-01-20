<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ProfileController;


// 1. Jalur Publik (Landing Page)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/track', [HomeController::class, 'track'])->name('track');

// 2. Jalur Admin (Dashboard & Sistem)
// Kita kelompokkan biar rapi dengan prefix "admin"

// GROUP 1: BISA DIAKSES SEMUA (Owner, Admin, Staff)
Route::middleware(['auth', 'role:owner,admin,staff'])->prefix('admin')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Operasional Harian (Kasir & Pelanggan)
    Route::resource('transactions', TransactionController::class);
    Route::get('/transactions/{transaction}/print-thermal', [TransactionController::class, 'printThermal'])->name('transactions.printThermal');
    Route::put('/transactions/{transaction}/update-status', [TransactionController::class, 'updateStatus'])->name('transactions.updateStatus');
    Route::resource('customers', CustomerController::class);
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// GROUP 2: HANYA OWNER & ADMIN (Staff Gak Boleh Masuk)
Route::middleware(['auth', 'role:owner,admin'])->prefix('admin')->group(function () {
    Route::get('/reports/export', [ReportController::class, 'exportExcel'])->name('reports.export');
    // Manajemen Paket & Laporan
    Route::resource('services', ServiceController::class);
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});

// GROUP 3: KHUSUS OWNER (Admin & Staff Gak Boleh Masuk)
Route::middleware(['auth', 'role:owner'])->prefix('admin')->group(function () {
    
    // Area Sensitif (Uang, User, Setting)
    Route::resource('expenses', ExpenseController::class);
    Route::resource('users', UserController::class);
    
    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
});

// --- [INI YANG TADI HILANG: Jalur Login/Register/Logout] ---
Illuminate\Support\Facades\Auth::routes(['register' => false]);
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// AREA PELANGGAN (Gen Z Area)
Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {

    // Dashboard Pelanggan
    Route::get('/dashboard', [App\Http\Controllers\Customer\OrderController::class, 'index'])->name('dashboard');

    // Halaman Booking Jemputan
    Route::get('/order', [App\Http\Controllers\Customer\OrderController::class, 'create'])->name('order.create');
    Route::post('/order', [App\Http\Controllers\Customer\OrderController::class, 'store'])->name('order.store');
});