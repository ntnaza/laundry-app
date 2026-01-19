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
    Schema::table('users', function (Blueprint $table) {
        // Enum: Owner (Bos), Admin (Manajer), Staff (Kasir/Bagian Cuci)
        $table->enum('role', ['owner', 'admin', 'staff'])->default('staff')->after('email');
        $table->boolean('is_active')->default(true)->after('role'); // Biar bisa blokir kasir resign
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
