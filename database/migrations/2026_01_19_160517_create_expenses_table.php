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
    Schema::create('expenses', function (Blueprint $table) {
        $table->id();
        $table->string('description'); // Contoh: Beli Deterjen, Bayar Listrik, Gaji Karyawan
        $table->integer('amount'); // Jumlah uang keluar
        $table->date('date'); // Tanggal pengeluaran
        $table->foreignId('user_id')->constrained('users'); // Siapa karyawan yang input (Penting buat tanggung jawab!)
        $table->text('note')->nullable(); // Catatan tambahan kalau perlu
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
