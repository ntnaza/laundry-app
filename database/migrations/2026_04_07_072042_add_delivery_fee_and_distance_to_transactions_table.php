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
            // Kita tambahkan kolom-kolom yang kurang untuk delivery
            if (!Schema::hasColumn('transactions', 'delivery_fee')) {
                $table->decimal('delivery_fee', 10, 2)->default(0)->after('total_price');
            }
            if (!Schema::hasColumn('transactions', 'distance')) {
                $table->decimal('distance', 8, 2)->default(0)->after('delivery_fee');
            }
            if (!Schema::hasColumn('transactions', 'subtotal')) {
                $table->decimal('subtotal', 12, 2)->default(0)->after('total_price');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['delivery_fee', 'distance', 'subtotal']);
        });
    }
};
