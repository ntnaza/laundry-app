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
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->string('invoice_code')->unique(); // INV-202310-001
        $table->foreignId('customer_id')->constrained('customers');
        $table->foreignId('user_id')->constrained('users'); // Kasir yang input
        $table->integer('total_price');
        // Status cucian: Baru masuk -> Cuci -> Setrika -> Siap -> Diambil
        $table->enum('status', ['pending', 'process', 'ready', 'done', 'canceled'])->default('pending');
        $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
        $table->string('payment_method')->nullable(); // Cash, QRIS, Transfer
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
