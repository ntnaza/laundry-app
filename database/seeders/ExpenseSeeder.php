<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        $expenses = [
            [
                'description' => 'Beli Deterjen & Pewangi',
                'amount' => 150000,
                'date' => now()->subDays(5)->format('Y-m-d'),
                'user_id' => $admin->id,
                'note' => 'Stok bulanan deterjen cair.',
            ],
            [
                'description' => 'Bayar Listrik',
                'amount' => 500000,
                'date' => now()->subDays(10)->format('Y-m-d'),
                'user_id' => $admin->id,
                'note' => 'Tagihan listrik bulan ini.',
            ],
            [
                'description' => 'Beli Plastik Packing',
                'amount' => 75000,
                'date' => now()->subDays(2)->format('Y-m-d'),
                'user_id' => $admin->id,
                'note' => 'Plastik ukuran besar dan sedang.',
            ],
            [
                'description' => 'Servis Mesin Cuci',
                'amount' => 200000,
                'date' => now()->subDays(15)->format('Y-m-d'),
                'user_id' => $admin->id,
                'note' => 'Ganti sparepart mesin cuci nomor 2.',
            ],
            [
                'description' => 'Gaji Staff',
                'amount' => 2000000,
                'date' => now()->subDays(1)->format('Y-m-d'),
                'user_id' => $admin->id,
                'note' => 'Gaji mingguan staff.',
            ],
        ];

        foreach ($expenses as $expense) {
            Expense::create($expense);
        }
    }
}
