<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionLog extends Model
{
    protected $guarded = ['id'];

    // 1. Ini tetap dibiarkan false (biar error updated_at hilang)
    public $timestamps = false;

    // 2. TAMBAHKAN INI (SOLUSI ERROR BARU)
    // Ini menyuruh Laravel: "Tolong kolom created_at tetap dianggap sebagai Tanggal (Datetime)"
    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}