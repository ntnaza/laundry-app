<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_spend',
        'max_discount',
        'start_date',
        'end_date',
        'is_active',
        'description'
    ];

    // Helper untuk cek apakah promo valid
    public function isValid()
    {
        $now = now();
        
        // Cek Status Aktif
        if (!$this->is_active) return false;

        // Cek Tanggal Mulai
        if ($this->start_date && $now->lt($this->start_date)) return false;

        // Cek Tanggal Berakhir
        if ($this->end_date && $now->gt($this->end_date)) return false;

        return true;
    }
}