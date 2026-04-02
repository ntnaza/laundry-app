<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Cuci Komplit (Cuci Kering Setrika)',
                'price' => 7000,
                'type' => 'Kiloan',
                'unit' => 'kg',
                'estimate_duration' => 48,
                'description' => 'Layanan lengkap cuci, kering, dan setrika rapi.',
            ],
            [
                'name' => 'Cuci Kering',
                'price' => 5000,
                'type' => 'Kiloan',
                'unit' => 'kg',
                'estimate_duration' => 24,
                'description' => 'Cuci dan kering saja, tanpa setrika.',
            ],
            [
                'name' => 'Setrika Saja',
                'price' => 4000,
                'type' => 'Kiloan',
                'unit' => 'kg',
                'estimate_duration' => 24,
                'description' => 'Setrika rapi pakaian yang sudah bersih.',
            ],
            [
                'name' => 'Bedcover Besar',
                'price' => 25000,
                'type' => 'Satuan',
                'unit' => 'pcs',
                'estimate_duration' => 72,
                'description' => 'Pencucian bedcover ukuran besar.',
            ],
            [
                'name' => 'Sepatu Casual',
                'price' => 30000,
                'type' => 'Satuan',
                'unit' => 'pcs',
                'estimate_duration' => 72,
                'description' => 'Deep cleaning untuk sepatu casual.',
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
