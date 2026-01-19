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
    Schema::create('testimonials', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Nama pelanggan
        $table->text('content'); // Isi review/testimoni
        $table->integer('rating')->default(5); // Bintang 1-5
        $table->string('photo')->nullable(); // Path foto pelanggan (opsional)
        $table->boolean('show_on_landing_page')->default(true); // Saklar buat nampilin/ngumpetin review di web depan
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
