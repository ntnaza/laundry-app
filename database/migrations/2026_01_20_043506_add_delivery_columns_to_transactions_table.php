<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('transactions', function (Blueprint $table) {
        // Kolom buat Alamat Jemput (Boleh kosong kalau dia datang sendiri)
        $table->text('pickup_address')->nullable()->after('status');

        // Kolom buat Koordinat Peta (Nanti kalau mau pake Google Maps, siapin aja dulu)
        $table->string('latitude')->nullable()->after('pickup_address');
        $table->string('longitude')->nullable()->after('latitude');

        // Status Pengiriman: 'pickup' (dijemput), 'delivery' (diantar balik), 'none' (ambil sendiri)
        $table->enum('delivery_type', ['pickup', 'delivery', 'both', 'none'])->default('none')->after('pickup_address');

        // Status Kurir: 'pending', 'on_the_way', 'delivered'
        $table->string('delivery_status')->default('pending')->after('delivery_type');
    });
}

public function down()
{
    Schema::table('transactions', function (Blueprint $table) {
        $table->dropColumn(['pickup_address', 'latitude', 'longitude', 'delivery_type', 'delivery_status']);
    });
}
};
