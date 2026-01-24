<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // INI KUNCINYA KOH. 'note' HARUS ADA DI SINI.
    protected $fillable = [
        'invoice_code',
        'customer_id',
        'user_id',
        'total_price',
        'status',
        'payment_status',
        'pickup_address',
        'latitude',
        'longitude',
        'delivery_type',
        'delivery_status',
        'payment_proof',
        'note',
        'promo_id',        // <--- Baru
        'discount_amount', // <--- Baru
        'subtotal'         // <--- Baru
    ];

    // Relasi ke Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relasi ke Promo
    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }

    // Relasi ke User (Admin yang nimbang)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Detail Item (Kalau nanti butuh)
    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function logs()
    {
        return $this->hasMany(TransactionLog::class);
    }

    // Helper untuk generate Link WhatsApp Dinamis
    public function generateWaLink()
    {
        $setting = Setting::first();
        $shopName = $setting->shop_name ?? 'LaundryKuy';
        $phone = $this->customer->phone;
        $name = $this->customer->name;
        $invoice = $this->invoice_code;
        $total = number_format($this->total_price, 0, ',', '.');
        $status = $this->status;

        // Template Pesan Berdasarkan Status
        if ($status == 'pending') {
            $msg = "Halo Kak *$name*! 👋\n\nPesanan laundry dengan No. Invoice *$invoice* telah kami terima di *$shopName*. Mohon tunggu sebentar ya, cucian Kakak akan segera kami proses! ✨";
        } elseif ($status == 'process') {
            $msg = "Halo Kak *$name*! 👋\n\nCucian Kakak dengan No. Invoice *$invoice* sedang dalam *PROSES PENCUCIAN* 🫧. Kami pastikan bersih, wangi, dan rapi sesuai standar *$shopName*!";
        } elseif ($status == 'ready') {
            $msg = "Kabar Gembira Kak *$name*! 😍\n\nCucian dengan No. Invoice *$invoice* sudah *SELESAI* dan siap diambil/diantar 🛍️. \nTotal Tagihan: *Rp $total*\n\nTerima kasih sudah mempercayakan pakaian Kakak kepada *$shopName*!";
        } elseif ($status == 'done') {
            $msg = "Halo Kak *$name*! 👋\n\nTerima kasih banyak sudah menggunakan jasa *$shopName* hari ini. Semoga suka dengan hasilnya ya! Ditunggu laundry berikutnya. Have a nice day! ✨❤️";
        } else {
            $msg = "Halo Kak *$name*, status pesanan *$invoice* Anda saat ini adalah: " . strtoupper($status);
        }

        // Tambahkan Link Tracking
        $msg .= "\n\nCek status detail disini:\n" . route('track');

        return "https://wa.me/$phone?text=" . urlencode($msg);
    }

    public function testimonial()
    {
        return $this->hasOne(Testimonial::class);
    }
}