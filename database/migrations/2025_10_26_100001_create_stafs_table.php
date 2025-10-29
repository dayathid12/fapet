<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stafs', function (Blueprint $table) {
            $table->id('staf_id');
            $table->string('nama_staf');
            $table->string('nip_staf')->unique()->nullable();
            $table->string('wa_staf')->nullable();
            $table->string('jabatan')->nullable(); // e.g., 'Pengemudi', 'Asisten'
            $table->string('id_nama')->nullable();
            $table->string('gol_pangkat')->nullable();
            $table->string('status')->nullable();
            $table->string('pendidikan_aktif')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->date('menuju_pensiun')->nullable();
            $table->string('kartu_pegawai')->nullable();
            $table->string('status_kepegawaian')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->string('no_ktp')->nullable();
            $table->string('no_npwp')->nullable();
            $table->string('no_bpjs_kesehatan')->nullable();
            $table->string('no_bpjs_ketenagakerjaan')->nullable();
            $table->string('no_telepon')->nullable();
            $table->string('email')->nullable();
            $table->text('alamat_rumah')->nullable();
            $table->string('rekening')->nullable();
            $table->string('nama_bank')->nullable();
            $table->string('status_aplikasi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stafs');
    }
};
