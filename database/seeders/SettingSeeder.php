<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'shop_name' => 'Berkah Laundry',
            'phone' => '081234567890',
            'address' => 'Jl. Kebon Jeruk No. 88, Jakarta Barat',
            'operating_hours' => '08:00 - 20:00',
            'latitude' => '-6.191837',
            'longitude' => '106.772596',
            'delivery_rate_per_km' => 2500,
        ]);
    }
}
