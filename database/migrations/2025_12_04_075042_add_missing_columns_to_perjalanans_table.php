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
        Schema::table('perjalanans', function (Blueprint $table) {
            $table->string('nama_personil_perwakilan')->nullable();
            $table->string('kontak_pengguna_perwakilan')->nullable();
            $table->string('status_sebagai')->nullable();
            $table->string('provinsi')->nullable();
            $table->text('uraian_singkat_kegiatan')->nullable();
            $table->text('catatan_keterangan_tambahan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perjalanans', function (Blueprint $table) {
            $table->dropColumn(['nama_personil_perwakilan', 'kontak_pengguna_perwakilan', 'status_sebagai', 'provinsi', 'uraian_singkat_kegiatan', 'catatan_keterangan_tambahan']);
        });
    }
};
