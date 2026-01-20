<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    // INI KUNCINYA: Izinkan semua kolom diisi
    protected $guarded = ['id']; 
}
