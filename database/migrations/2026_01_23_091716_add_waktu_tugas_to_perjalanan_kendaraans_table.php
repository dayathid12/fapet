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
        Schema::table('perjalanan_kendaraans', function (Blueprint $table) {
            $table->dateTime('waktu_mulai_tugas')->nullable()->after('kendaraan_nopol');
            $table->dateTime('waktu_selesai_tugas')->nullable()->after('waktu_mulai_tugas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perjalanan_kendaraans', function (Blueprint $table) {
            $table->dropColumn(['waktu_mulai_tugas', 'waktu_selesai_tugas']);
        });
    }
};