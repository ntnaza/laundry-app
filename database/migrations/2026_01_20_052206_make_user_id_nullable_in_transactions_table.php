<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // <--- Jangan lupa ini

return new class extends Migration
{
    public function up()
    {
        // Ubah kolom user_id jadi NULLABLE (Boleh Kosong)
        // Kita pakai RAW SQL biar aman dari error dependency
        DB::statement("ALTER TABLE transactions MODIFY COLUMN user_id BIGINT UNSIGNED NULL");
    }

    public function down()
    {
        // Balikin jadi WAJIB ISI (NOT NULL) kalau di-rollback
        // Pastikan tidak ada data yang null sebelum rollback, atau akan error
        DB::statement("ALTER TABLE transactions MODIFY COLUMN user_id BIGINT UNSIGNED NOT NULL");
    }
};