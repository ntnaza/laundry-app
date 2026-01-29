<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    // INI KUNCINYA: Izinkan semua kolom diisi
    protected $fillable = [
        'shop_name',
        'phone',
        'address',
        'operating_hours',
        'logo',
        'latitude',
        'longitude',
        'delivery_rate_per_km'
    ];
}
