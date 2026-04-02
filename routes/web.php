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
Route::get('/home', function() {
    return redirect('/');
});
Route::post('/track', [HomeController::class, 'track'])->name('track');

// Route Midtrans (Harus Publik buat Callback)
Route::post('/midtrans/callback', [App\Http\Controllers\Customer\OrderController::class, 'callback']); 

// 2. OTENTIKASI (Login/Register/Logout)
Auth::routes(['verify' => true]);

// 3. JALUR ADMIN (Dashboard & Sistem)
// GROUP 1: BISA DIAKSES SEMUA (Owner, Admin, Staff)
Route::middleware(['auth', 'verified', 'role:owner,admin,staff'])->prefix('admin')->group(function () {
    
    // Dashboard & Radar Notifikasi
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/check-new-orders', [DashboardController::class, 'checkNewOrders'])->name('check_new_orders'); // <--- Route Polling Admin

    // Operasional Harian
    Route::resource('transactions', TransactionController::class);
    Route::get('/transactions/{transaction}/print-thermal', [TransactionController::class, 'printThermal'])->name('transactions.printThermal');
    Route::get('/transactions/{transaction}/print-invoice', [TransactionController::class, 'printInvoice'])->name('transactions.printInvoice'); // <--- Route Baru
    Route::get('/transactions/{transaction}/print-delivery', [TransactionController::class, 'printDelivery'])->name('transactions.printDelivery');
    Route::put('/transactions/{transaction}/update-status', [TransactionController::class, 'updateStatus'])->name('transactions.updateStatus');
    Route::put('/transactions/{transaction}/assign-courier', [TransactionController::class, 'assignCourier'])->name('transactions.assignCourier'); // <--- Route Assign Kurir
    
    // Data Pelanggan & Profil Diri
    Route::resource('customers', CustomerController::class);
    
    // Stok Barang (Inventaris)
    Route::resource('inventories', \App\Http\Controllers\InventoryController::class);

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// GROUP 2: HANYA OWNER & ADMIN (Staff Gak Boleh Masuk)
Route::middleware(['auth', 'role:owner,admin'])->prefix('admin')->group(function () {
    // Laporan Excel
    Route::get('/reports/export', [ReportController::class, 'exportExcel'])->name('reports.export');
    Route::get('/reports/profit', [ReportController::class, 'profit'])->name('reports.profit'); // <--- Route Baru
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // Manajemen Paket Laundry
    Route::resource('services', ServiceController::class);
    
    // Manajemen Promo & Diskon
    Route::resource('promos', \App\Http\Controllers\PromoController::class);
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
Route::middleware(['auth', 'verified'])->prefix('customer')->name('customer.')->group(function () {

    // Dashboard Pelanggan
    Route::get('/dashboard', [OrderController::class, 'index'])->name('dashboard');
    Route::get('/check-status', [OrderController::class, 'checkStatus'])->name('check_status'); // <--- Route Polling Customer

    // Order & Maps
    Route::get('/order', [OrderController::class, 'create'])->name('order.create');
    Route::post('/order', [OrderController::class, 'store'])->name('order.store');
    Route::post('/order/{id}/complete', [OrderController::class, 'complete'])->name('order.complete'); // <--- TAHAP 2 (Finalisasi)
    
    // Payment (Midtrans)
    Route::get('/order/{id}/pay', [OrderController::class, 'pay'])->name('order.pay'); // <--- Route Baru

    // Upload Bukti Bayar
    Route::post('/order/{id}/upload-proof', [OrderController::class, 'uploadProof'])->name('order.uploadProof');
    
    // Kirim Review
    Route::post('/order/{id}/review', [OrderController::class, 'storeReview'])->name('order.review');

    // Akses Profile untuk Customer
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// 5. JALUR DRIVER (Kurir)
Route::middleware(['auth', 'verified', 'role:driver'])->prefix('driver')->name('driver.')->group(function () {
    Route::get('/tasks', [\App\Http\Controllers\DriverController::class, 'index'])->name('tasks');
    Route::get('/history', [\App\Http\Controllers\DriverController::class, 'history'])->name('history');
    Route::put('/tasks/{id}/update', [\App\Http\Controllers\DriverController::class, 'updateStatus'])->name('updateStatus');
    
    // Akses Profile untuk Driver
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});