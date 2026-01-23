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
        'note' // <--- Pastikan ini ada!
    ];

    // Relasi ke Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
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
}