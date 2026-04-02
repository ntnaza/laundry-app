<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin Laundry',
                'email' => 'miaterbean159@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Staff Laundry',
                'email' => 'sultanmalikahmad2007@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'staff',
            ],
            [
                'name' => 'Owner Laundry',
                'email' => 'rizkypertamagata@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'owner',
            ],
            [
                'name' => 'Driver Laundry',
                'email' => 'torisamarinda@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'driver',
            ],
            [
                'name' => 'Customer Laundry',
                'email' => 'pribodogkulimihi@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
