<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Customer;
use App\Models\Transaction;

echo "Mulai Memperbaiki Data Pesanan Lama...\n";

$users = User::where('role', 'customer')->get();

foreach ($users as $user) {
    echo "Memproses User: {$user->name} ({$user->phone})\n";

    // Cari data customer yang nomor HP-nya sama dengan user ini
    $customers = Customer::where('phone', $user->phone)->get();

    foreach ($customers as $customer) {
        // Update transaksi milik customer ini
        $affected = Transaction::where('customer_id', $customer->id)
            ->whereNull('app_user_id')
            ->update(['app_user_id' => $user->id]);
        
        if ($affected > 0) {
            echo "  -> Berhasil mengaitkan $affected pesanan lama ke akun ini.\n";
        }
    }
}

echo "Selesai! Sekarang cek dashboard pelanggan.\n";

