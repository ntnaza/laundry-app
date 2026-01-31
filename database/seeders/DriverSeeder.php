<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Cari user yang rolenya 'driver'
        $drivers = User::where('role', 'driver')->get();

        if ($drivers->count() > 0) {
            foreach ($drivers as $driver) {
                // Reset password jadi 'password' (ter-hash dengan benar)
                $driver->update([
                    'password' => Hash::make('password')
                ]);
                $this->command->info("Password untuk driver '{$driver->name}' telah di-reset menjadi: password");
            }
        } else {
            // 2. Kalau belum ada driver, kita buatkan satu
            User::create([
                'name' => 'Budi Kurir',
                'email' => 'driver@laundry.com',
                'password' => Hash::make('password'),
                'role' => 'driver',
                'email_verified_at' => now(),
            ]);
            $this->command->info("Akun driver baru dibuat: driver@laundry.com / password");
        }
    }
}
