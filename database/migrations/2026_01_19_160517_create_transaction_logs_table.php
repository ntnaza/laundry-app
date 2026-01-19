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
    Schema::create('transaction_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
        $table->string('status'); // Status yang diubah
        $table->foreignId('user_id')->nullable()->constrained('users'); // Siapa yang update status
        $table->timestamp('created_at')->useCurrent(); // Waktu kejadian
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_logs');
    }
};
