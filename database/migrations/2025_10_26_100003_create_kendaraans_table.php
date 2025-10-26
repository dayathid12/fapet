<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kendaraans', function (Blueprint $table) {
            $table->string('nopol_kendaraan')->primary();
            $table->string('jenis_kendaraan');
            $table->string('merk_type')->nullable();
            $table->string('warna_tanda')->nullable();
            $table->year('tahun_pembuatan')->nullable();
            $table->string('nomor_rangka')->unique()->nullable();
            $table->string('nomor_mesin')->unique()->nullable();
            $table->string('jenis_bbm_default')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kendaraans');
    }
};
