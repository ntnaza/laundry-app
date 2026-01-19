<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $guarded = ['id'];

    // Relasi ke User (Karyawan/Admin yang lapor pengeluaran)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}