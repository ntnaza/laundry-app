<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $guarded = ['id']; // Semua kolom boleh diisi kecuali ID

    // Relasi ke Bahan Baku (Inventory)
    public function materials()
    {
        return $this->belongsToMany(Inventory::class, 'service_materials')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}