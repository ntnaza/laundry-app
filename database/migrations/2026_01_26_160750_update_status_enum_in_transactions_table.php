<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah kolom status untuk menambahkan opsi 'draft'
        DB::statement("ALTER TABLE transactions MODIFY COLUMN status ENUM('draft','pending','process','washing','ironing','ready','done','canceled') DEFAULT 'draft'");
    }

    public function down(): void
    {
        // Kembalikan ke semula (hapus draft)
        // Note: Data yang statusnya 'draft' akan error/kosong jika ini dijalankan.
        DB::statement("ALTER TABLE transactions MODIFY COLUMN status ENUM('pending','process','washing','ironing','ready','done','canceled') DEFAULT 'pending'");
    }
};