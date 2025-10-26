<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biaya_tols', function (Blueprint $table) {
            $table->id('tol_id');
            $table->foreignId('nomor_perjalanan')->constrained('perjalanans', 'nomor_perjalanan');
            $table->string('lokasi_tol');
            $table->string('kode_kartu_tol');
            $table->decimal('biaya_tol', 15, 2);
            $table->string('gambar_bukti')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biaya_tols');
    }
};
