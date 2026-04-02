<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('role', 'customer')->first();
        $transactions = Transaction::where('status', 'done')->get();

        $testimonials = [
            [
                'user_id' => $user->id,
                'transaction_id' => $transactions->first()?->id,
                'name' => 'Budi Santoso',
                'rate' => 5,
                'content' => 'Pelayanan sangat cepat dan baju harum sekali! Sangat direkomendasikan.',
            ],
            [
                'user_id' => $user->id,
                'transaction_id' => null,
                'name' => 'Siti Aminah',
                'rate' => 4,
                'content' => 'Bersih banget, cuma sayang kemarin agak telat sedikit jemputnya.',
            ],
            [
                'user_id' => $user->id,
                'transaction_id' => null,
                'name' => 'Andi Wijaya',
                'rate' => 5,
                'content' => 'Terima kasih Berkah Laundry, sepatu saya jadi kayak baru lagi.',
            ],
            [
                'user_id' => $user->id,
                'transaction_id' => null,
                'name' => 'Dewi Lestari',
                'rate' => 5,
                'content' => 'Aplikasi ini sangat memudahkan buat pesan laundry antar jemput.',
            ],
            [
                'user_id' => $user->id,
                'transaction_id' => null,
                'name' => 'Rizky Pratama',
                'rate' => 4,
                'content' => 'Harga terjangkau dan kualitas cucian memuaskan.',
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}
