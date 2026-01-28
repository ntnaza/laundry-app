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
        // UPDATE: Kita skip transactions karena sudah ada/sebagian masuk
        // Kita fokus ke Settings saja
        
        if (!Schema::hasColumn('settings', 'latitude')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->string('latitude')->nullable()->comment('Koordinat Toko');
                $table->string('longitude')->nullable()->comment('Koordinat Toko');
                $table->decimal('delivery_rate_per_km', 10, 2)->default(2000)->comment('Harga per KM');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'delivery_rate_per_km']);
        });
    }
};