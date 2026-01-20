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
    // Cek dulu biar gak error kalau tabelnya udah ada
    if (!Schema::hasTable('expenses')) {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('description'); // Beli Sabun, Bayar Listrik
            $table->bigInteger('amount');  // 50000
            $table->date('date');          // Tanggal keluar duit
            $table->text('note')->nullable(); // Catatan tambahan
            $table->timestamps();
        });
    }
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
