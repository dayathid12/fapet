<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perjalanans', function (Blueprint $table) {
            $table->id('nomor_perjalanan');
            $table->dateTime('waktu_keberangkatan');
            $table->dateTime('waktu_kepulangan')->nullable();
            $table->string('status_perjalanan');
            $table->text('alamat_tujuan');
            $table->string('lokasi_keberangkatan');
            $table->integer('jumlah_rombongan');
            $table->string('jenis_kegiatan');
            $table->string('nama_kegiatan');
            $table->string('jenis_operasional');
            $table->string('status_operasional');
            $table->string('no_surat_tugas')->unique();
            $table->string('file_surat_jalan')->nullable();
            $table->string('docs_surat_tugas')->nullable();
            $table->string('upload_surat_tugas')->nullable();
            $table->string('download_file')->nullable();
            $table->boolean('status_cek_1')->default(false);
            $table->boolean('status_cek_2')->default(false);

            $table->string('nama_pengguna')->nullable();
            $table->string('kontak_pengguna')->nullable();
            $table->foreignId('pengemudi_id')->constrained('stafs', 'staf_id');
            $table->foreignId('asisten_id')->nullable()->constrained('stafs', 'staf_id');
            $table->string('nopol_kendaraan');
            $table->foreign('nopol_kendaraan')->references('nopol_kendaraan')->on('kendaraans');
            $table->foreignId('tujuan_wilayah_id')->constrained('wilayahs', 'wilayah_id');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perjalanans');
    }
};
