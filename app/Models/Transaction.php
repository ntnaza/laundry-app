<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = ['id']; // Semua kolom boleh diisi

    // Relasi ke Pelanggan
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relasi ke Kasir (User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Detail Item
    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    // Relasi ke Log
    public function logs()
    {
        return $this->hasMany(TransactionLog::class)->latest();
    }
}