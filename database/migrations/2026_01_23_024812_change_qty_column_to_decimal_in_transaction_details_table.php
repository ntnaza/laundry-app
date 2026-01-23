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
        Schema::table('transaction_details', function (Blueprint $table) {
            // Ubah qty dari integer ke decimal (8 digit total, 2 di belakang koma)
            // Contoh: 123456.78
            $table->decimal('qty', 10, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            // Balikin ke integer kalau rollback
            $table->integer('qty')->change();
        });
    }
};