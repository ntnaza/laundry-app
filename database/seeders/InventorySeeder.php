<?php

namespace Database\Seeders;

use App\Models\Inventory;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inventories = [
            [
                'name' => 'Deterjen Cair 5L',
                'stock' => 10,
                'unit' => 'Jerigen',
                'min_stock' => 2,
                'price' => 55000,
                'note' => 'Aroma Lavender.',
            ],
            [
                'name' => 'Pewangi Pakaian 5L',
                'stock' => 8,
                'unit' => 'Jerigen',
                'min_stock' => 2,
                'price' => 45000,
                'note' => 'Aroma Sakura.',
            ],
            [
                'name' => 'Plastik Packing Besar',
                'stock' => 500,
                'unit' => 'Pcs',
                'min_stock' => 50,
                'price' => 500,
                'note' => 'Untuk Bedcover.',
            ],
            [
                'name' => 'Plastik Packing Sedang',
                'stock' => 1000,
                'unit' => 'Pcs',
                'min_stock' => 100,
                'price' => 300,
                'note' => 'Untuk baju kiloan.',
            ],
            [
                'name' => 'Hanger Besi',
                'stock' => 100,
                'unit' => 'Pcs',
                'min_stock' => 20,
                'price' => 2500,
                'note' => 'Untuk satuan.',
            ],
        ];

        foreach ($inventories as $inventory) {
            Inventory::create($inventory);
        }
    }
}
