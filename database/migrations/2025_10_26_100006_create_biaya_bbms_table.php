<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biaya_bbms', function (Blueprint $table) {
            $table->id('bbm_id');
            $table->foreignId('nomor_perjalanan')->constrained('perjalanans', 'nomor_perjalanan');
            $table->string('kode_bbm_metode_bayar');
            $table->string('kode_atm')->nullable();
            $table->string('jenis_bbm_diisi');
            $table->decimal('volume_liter', 8, 2);
            $table->decimal('biaya_bbm', 15, 2);
            $table->string('gambar_bukti')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biaya_bbms');
    }
};
