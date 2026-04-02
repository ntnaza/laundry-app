<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\TransactionLog;
use App\Models\Customer;
use App\Models\User;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::all();
        $admin = User::where('role', 'admin')->first();
        $staff = User::where('role', 'staff')->first();
        $driver = User::where('role', 'driver')->first();
        $services = Service::all();

        $statuses = ['pending', 'process', 'washing', 'ironing', 'ready', 'done'];

        for ($i = 0; $i < 5; $i++) {
            $customer = $customers->random();
            $service = $services->random();
            $qty = ($service->type == 'Kiloan') ? rand(2, 5) : rand(1, 2);
            $total_price = $service->price * $qty;

            $transaction = Transaction::create([
                'invoice_code' => 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
                'customer_id' => $customer->id,
                'user_id' => rand(0, 1) ? $admin->id : $staff->id,
                'courier_id' => $driver->id,
                'total_price' => $total_price,
                'status' => $statuses[$i],
                'payment_status' => ($i > 3) ? 'paid' : 'unpaid',
                'payment_method' => ($i > 3) ? 'Cash' : null,
                'created_at' => now()->subDays(5 - $i),
            ]);

            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'service_id' => $service->id,
                'qty' => $qty,
                'price_per_unit' => $service->price,
                'subtotal' => $total_price,
            ]);

            TransactionLog::create([
                'transaction_id' => $transaction->id,
                'status' => $statuses[$i],
                'user_id' => $transaction->user_id,
            ]);
        }
    }
}
