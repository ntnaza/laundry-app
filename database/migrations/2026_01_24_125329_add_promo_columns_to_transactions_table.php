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
            // Tambah relasi ke promo (nullable karena gak semua pakai promo)
            $table->foreignId('promo_id')->nullable()->constrained('promos')->nullOnDelete()->after('user_id');
            
            // Simpan nominal diskon yang didapat
            $table->decimal('discount_amount', 15, 2)->default(0)->after('total_price');
            
            // Simpan harga asli sebelum diskon (biar report gampang)
            $table->decimal('subtotal', 15, 2)->default(0)->after('total_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['promo_id']);
            $table->dropColumn(['promo_id', 'discount_amount', 'subtotal']);
        });
    }
};