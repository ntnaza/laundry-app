<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Update Enum Role di tabel Users
        // Kita gunakan raw statement karena Laravel tidak support native enum modification di beberapa driver
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'staff', 'owner', 'customer', 'driver') NOT NULL DEFAULT 'customer'");

        // 2. Tambah kolom courier_id di transactions
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('courier_id')->nullable()->after('user_id')->comment('ID Driver yang bertugas');
            
            // Tambahkan Foreign Key agar datanya konsisten
            $table->foreign('courier_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Hapus kolom courier_id
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['courier_id']);
            $table->dropColumn('courier_id');
        });

        // 2. Kembalikan enum role seperti semula
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'staff', 'owner', 'customer') NOT NULL DEFAULT 'customer'");
    }
};
