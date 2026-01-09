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
        Schema::table('sptjb_uang_pengemudi_details', function (Blueprint $table) {
            $table->date('tanggal_penugasan')->nullable()->change();
            $table->integer('jumlah_hari')->nullable()->change();
            $table->decimal('besaran_uang_per_hari', 15, 2)->nullable()->change();
            $table->decimal('jumlah_rp', 15, 2)->nullable()->change();
            $table->decimal('jumlah_uang_diterima', 15, 2)->nullable()->change();
            $table->string('nomor_rekening')->nullable()->change();
            $table->string('golongan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sptjb_uang_pengemudi_details', function (Blueprint $table) {
            $table->date('tanggal_penugasan')->nullable(false)->change();
            $table->integer('jumlah_hari')->nullable(false)->change();
            $table->decimal('besaran_uang_per_hari', 15, 2)->nullable(false)->change();
            $table->decimal('jumlah_rp', 15, 2)->nullable(false)->change();
            $table->decimal('jumlah_uang_diterima', 15, 2)->nullable(false)->change();
            $table->string('nomor_rekening')->nullable(false)->change();
            $table->string('golongan')->nullable(false)->change();
        });
    }
};
