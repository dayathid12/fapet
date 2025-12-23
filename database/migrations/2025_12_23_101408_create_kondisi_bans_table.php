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
        Schema::create('kondisi_bans', function (Blueprint $table) {
            $table->id();
            $table->string('nopol_kendaraan');
            $table->foreign('nopol_kendaraan')->references('nopol_kendaraan')->on('kendaraans')->onDelete('cascade');
            $table->string('ban_depan_kiri')->nullable();
            $table->string('ban_depan_kanan')->nullable();
            $table->string('ban_belakang_kiri')->nullable();
            $table->string('ban_belakang_kanan')->nullable();
            $table->integer('odo_terbaru')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kondisi_bans');
    }
};
