<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penerima_sptjbs', function (Blueprint $table) {
            $table->id('penerima_id');
            $table->string('no_sptjb');
            $table->foreign('no_sptjb')->references('no_sptjb')->on('sptjbs');
            $table->string('nama');
            $table->string('jabatan');
            $table->string('golongan')->nullable();
            $table->date('tanggal_penugasan');
            $table->decimal('besarannya_harian', 15, 2);
            $table->integer('jumlah_hari');
            $table->decimal('jumlah_diterima', 15, 2);
            $table->string('nomor_rekening')->nullable();
            $table->string('nama_bank')->nullable();
            $table->string('nomor_surat_tugas');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penerima_sptjbs');
    }
};
