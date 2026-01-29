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
        Schema::create('notifikasi_w_a_s', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->unique(); // Nomor sebagai key unik
            $table->string('judul'); // Judul notifikasi
            $table->text('isi_pesan'); // Isi pesan untuk karakter panjang
            $table->string('number_key'); // Number Key contoh: 32SLSmpe9fiqBcP9
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi_w_a_s');
    }
};
