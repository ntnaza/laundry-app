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
        Schema::table('transactions', function (Blueprint $table) {
            // Kolom ini mencatat ID User (Pelanggan) yang membuat pesanan via aplikasi.
            // Berbeda dengan 'user_id' yang dipakai untuk mencatat Admin/Kasir yang memproses.
            $table->foreignId('app_user_id')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['app_user_id']);
            $table->dropColumn('app_user_id');
        });
    }
};
