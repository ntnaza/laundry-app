<?php

namespace Database\Seeders;

use App\Models\Promo;
use Illuminate\Database\Seeder;

class PromoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $promos = [
            [
                'code' => 'WELCOME50',
                'type' => 'percentage',
                'value' => 50,
                'min_spend' => 20000,
                'max_discount' => 10000,
                'is_active' => true,
                'description' => 'Diskon 50% untuk pelanggan baru.',
            ],
            [
                'code' => 'HEMAT10',
                'type' => 'fixed',
                'value' => 10000,
                'min_spend' => 50000,
                'is_active' => true,
                'description' => 'Potongan 10rb minimal belanja 50rb.',
            ],
            [
                'code' => 'MERDEKA',
                'type' => 'percentage',
                'value' => 17,
                'min_spend' => 0,
                'is_active' => true,
                'description' => 'Diskon kemerdekaan 17%.',
            ],
            [
                'code' => 'CUCIBERSIH',
                'type' => 'fixed',
                'value' => 5000,
                'min_spend' => 30000,
                'is_active' => true,
                'description' => 'Potongan 5rb minimal belanja 30rb.',
            ],
            [
                'code' => 'JUMATBERKAH',
                'type' => 'fixed',
                'value' => 2000,
                'min_spend' => 10000,
                'is_active' => true,
                'description' => 'Potongan 2rb khusus hari jumat.',
            ],
        ];

        foreach ($promos as $promo) {
            Promo::create($promo);
        }
    }
}
