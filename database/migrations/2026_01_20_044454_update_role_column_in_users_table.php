<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // <--- JANGAN LUPA INI!

return new class extends Migration
{
    public function up()
    {
        // Kita ubah kolom 'role' biar bisa nerima 'customer'
        // Defaultnya kita set jadi 'customer' biar aman
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'staff', 'owner', 'customer') NOT NULL DEFAULT 'customer'");
    }

    public function down()
    {
        // Balikin ke asal (kalau di-rollback)
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'staff', 'owner') NOT NULL DEFAULT 'staff'");
    }
};