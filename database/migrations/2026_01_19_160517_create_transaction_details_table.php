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
    Schema::create('transaction_details', function (Blueprint $table) {
        $table->id();
        $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
        $table->foreignId('service_id')->constrained('services');
        $table->integer('qty'); // Berat (kg) atau Jumlah (pcs)
        $table->integer('price_per_unit'); // Harga saat transaksi (biar kalau harga naik, history aman)
        $table->integer('subtotal');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};
