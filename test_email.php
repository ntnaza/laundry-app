<?php
if (php_sapi_name() !== 'cli') {
    die('Akses ditolak. Script ini hanya bisa dijalankan melalui terminal/CLI untuk keamanan.');
}

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- DEBUG KONFIGURASI EMAIL ---\n";
echo "Mailer: " . Config::get('mail.default') . "\n";
echo "Host: " . Config::get('mail.mailers.smtp.host') . "\n";
echo "Port: " . Config::get('mail.mailers.smtp.port') . "\n";
echo "Encryption: " . Config::get('mail.mailers.smtp.encryption') . "\n";
echo "From Address: " . Config::get('mail.from.address') . "\n";
echo "From Name: " . Config::get('mail.from.name') . "\n";
echo "Queue: " . Config::get('queue.default') . "\n";
echo "-------------------------------\n\n";

try {
    echo "Sedang mencoba mengirim email...\n";
    $result = Mail::raw('Halo! Ini adalah email uji coba detail dari aplikasi Laundry.', function ($message) {
        $message->to('test@example.com')->subject('Tes Pengiriman Email Detail');
    });
    echo "HASIL: Email berhasil diproses oleh Laravel!\n";
    echo "Catatan: Jika Mailer=log, cek storage/logs/laravel.log\n";
    echo "Catatan: Jika Queue=database dan Mailer=smtp, jalankan 'php artisan queue:work'\n";
} catch (\Exception $e) {
    echo "GAGAL mengirim email!\n";
    echo "Pesan Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getFile() . " baris " . $e->getLine() . "\n";
}
