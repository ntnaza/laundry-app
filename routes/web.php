<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Tambahkan ini biar rapi
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
use App\Http\Controllers\Customer\OrderController; // Panggil Controller Customer

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. JALUR PUBLIK (Landing Page & Tracking)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/track', [HomeController::class, 'track'])->name('track');

// 2. OTENTIKASI (Login/Register/Logout)
Auth::routes(); // Biarkan default biar orang bisa register

// 3. JALUR ADMIN (Dashboard & Sistem)
// GROUP 1: BISA DIAKSES SEMUA (Owner, Admin, Staff)
Route::middleware(['auth', 'role:owner,admin,staff'])->prefix('admin')->group(function () {
    
    // Dashboard & Radar Notifikasi
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/check-orders', [DashboardController::class, 'checkNewOrders'])->name('admin.check_orders'); // <--- INI PENTING BUAT NOTIF

    // Operasional Harian
    Route::resource('transactions', TransactionController::class);
    Route::get('/transactions/{transaction}/print-thermal', [TransactionController::class, 'printThermal'])->name('transactions.printThermal');
    Route::get('/transactions/{transaction}/print-delivery', [TransactionController::class, 'printDelivery'])->name('transactions.printDelivery');
    Route::put('/transactions/{transaction}/update-status', [TransactionController::class, 'updateStatus'])->name('transactions.updateStatus');
    Route::post('/order/{id}/upload-proof', [OrderController::class, 'uploadProof'])->name('order.uploadProof');
    // Data Pelanggan & Profil Diri
    Route::resource('customers', CustomerController::class);
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// GROUP 2: HANYA OWNER & ADMIN (Staff Gak Boleh Masuk)
Route::middleware(['auth', 'role:owner,admin'])->prefix('admin')->group(function () {
    // Laporan Excel
    Route::get('/reports/export', [ReportController::class, 'exportExcel'])->name('reports.export');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // Manajemen Paket Laundry
    Route::resource('services', ServiceController::class);
});

// GROUP 3: KHUSUS OWNER (Admin & Staff Gak Boleh Masuk)
Route::middleware(['auth', 'role:owner'])->prefix('admin')->group(function () {
    // Keuangan & User
    Route::resource('expenses', ExpenseController::class);
    Route::resource('users', UserController::class);
    
    // Pengaturan Toko
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update'); // Pakai POST sesuai form
});


// 4. JALUR PELANGGAN (Customer Area)
Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {

    // Dashboard Pelanggan
    Route::get('/dashboard', [OrderController::class, 'index'])->name('dashboard');

    // Order & Maps
    Route::get('/order', [OrderController::class, 'create'])->name('order.create');
    Route::post('/order', [OrderController::class, 'store'])->name('order.store');
});