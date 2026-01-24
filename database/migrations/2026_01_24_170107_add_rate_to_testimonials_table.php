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
            // Cek satu-satu, tambahkan jika belum ada
            if (!Schema::hasColumn('testimonials', 'user_id')) {
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            }
            if (!Schema::hasColumn('testimonials', 'transaction_id')) {
                $table->foreignId('transaction_id')->nullable()->constrained('transactions')->cascadeOnDelete();
            }
            if (!Schema::hasColumn('testimonials', 'rate')) {
                $table->integer('rate')->default(5); // Default bintang 5 biar aman
            }
            if (!Schema::hasColumn('testimonials', 'content')) {
                $table->text('content')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn(['rate', 'content', 'transaction_id', 'user_id']);
        });
    }
};