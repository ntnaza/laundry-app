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
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // KODE PROMO (Misal: MERDEKA45)
            $table->enum('type', ['percentage', 'fixed'])->default('fixed'); // Tipe: Persen atau Rupiah
            $table->decimal('value', 15, 2); // Nilainya (10% atau 5000)
            $table->decimal('min_spend', 15, 2)->default(0); // Minimal belanja
            $table->decimal('max_discount', 15, 2)->nullable(); // Max potongan (khusus persen)
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};