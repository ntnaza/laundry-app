<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Menggunakan Raw SQL untuk mengubah kolom ENUM (Lebih aman di MySQL)
        DB::statement("ALTER TABLE transactions MODIFY COLUMN status ENUM('pending', 'process', 'washing', 'ironing', 'ready', 'done', 'canceled') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke status lama
        DB::statement("ALTER TABLE transactions MODIFY COLUMN status ENUM('pending', 'process', 'ready', 'done', 'canceled') DEFAULT 'pending'");
    }
};
