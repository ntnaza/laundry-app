<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userCustomer = User::where('email', 'pribodogkulimihi@gmail.com')->first();

        $customers = [
            [
                'name' => 'Budi Santoso',
                'phone' => '081234567890',
                'address' => 'Jl. Merdeka No. 1, Jakarta',
                'user_id' => $userCustomer->id,
            ],
            [
                'name' => 'Siti Aminah',
                'phone' => '081234567891',
                'address' => 'Jl. Mawar No. 12, Bandung',
                'user_id' => null,
            ],
            [
                'name' => 'Andi Wijaya',
                'phone' => '081234567892',
                'address' => 'Jl. Melati No. 45, Surabaya',
                'user_id' => null,
            ],
            [
                'name' => 'Dewi Lestari',
                'phone' => '081234567893',
                'address' => 'Jl. Kenanga No. 78, Yogyakarta',
                'user_id' => null,
            ],
            [
                'name' => 'Rizky Pratama',
                'phone' => '081234567894',
                'address' => 'Jl. Anggrek No. 3, Semarang',
                'user_id' => null,
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
