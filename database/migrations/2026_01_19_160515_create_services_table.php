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
    Schema::create('services', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Cuci Komplit, Bedcover, dll
        $table->integer('price');
        $table->string('type'); // Kiloan / Satuan
        $table->string('unit'); // kg / pcs / meter
        $table->integer('estimate_duration'); // Dalam jam (misal 24 jam)
        $table->text('description')->nullable(); // Buat ditampilkan di Landing Page
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
