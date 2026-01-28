<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'stock',
        'unit',
        'min_stock',
        'price',
        'note'
    ];

    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_materials')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}