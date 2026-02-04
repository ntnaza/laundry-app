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
        Schema::table('testimonials', function (Blueprint $table) {
            // Kita buat kolom 'name' jadi nullable karena data diambil dari relasi User
            $table->string('name')->nullable()->change();
            
            // Kolom 'rating' lama (mungkin sisa migrasi lama) juga dibikin nullable biar ga error
            if (Schema::hasColumn('testimonials', 'rating')) {
                $table->integer('rating')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            // Kembalikan ke not null (hati-hati data harus ada isinya)
            // $table->string('name')->nullable(false)->change(); 
        });
    }
};