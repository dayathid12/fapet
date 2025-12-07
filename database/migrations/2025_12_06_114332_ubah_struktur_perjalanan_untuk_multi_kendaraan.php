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
        // Membuat tabel baru untuk menyimpan relasi multi-kendaraan
        Schema::create('perjalanan_kendaraans', function (Blueprint $table) {
            $table->id();
            
            // Foreign key ke tabel 'perjalanans'
            $table->unsignedBigInteger('perjalanan_id');
            $table->foreign('perjalanan_id')->references('nomor_perjalanan')->on('perjalanans')->cascadeOnDelete();

            // Foreign key ke tabel 'kendaraans' (nopol_kendaraan adalah PK dan string)
            $table->string('kendaraan_nopol');
            $table->foreign('kendaraan_nopol')->references('nopol_kendaraan')->on('kendaraans')->cascadeOnDelete();

            // Foreign key ke tabel 'stafs' untuk pengemudi
            $table->unsignedBigInteger('pengemudi_id');
            $table->foreign('pengemudi_id')->references('staf_id')->on('stafs')->cascadeOnDelete();

            // Foreign key ke tabel 'stafs' untuk asisten (nullable)
            $table->unsignedBigInteger('asisten_id')->nullable();
            $table->foreign('asisten_id')->references('staf_id')->on('stafs')->nullOnDelete();
            
            $table->timestamps();
        });

        // Menghapus kolom lama dari tabel perjalanans
        Schema::table('perjalanans', function (Blueprint $table) {
            // Hapus foreign key constraint terlebih dahulu, baru kolomnya
            if (Schema::hasColumn('perjalanans', 'nopol_kendaraan')) {
                // Asumsi nama constraint default: perjalanans_nopol_kendaraan_foreign
                $table->dropForeign(['nopol_kendaraan']);
                $table->dropColumn('nopol_kendaraan');
            }
            if (Schema::hasColumn('perjalanans', 'pengemudi_id')) {
                // Asumsi nama constraint default: perjalanans_pengemudi_id_foreign
                $table->dropForeign(['pengemudi_id']);
                $table->dropColumn('pengemudi_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus tabel baru
        Schema::dropIfExists('perjalanan_kendaraans');

        // Tambahkan kembali kolom lama ke tabel perjalanans
        Schema::table('perjalanans', function (Blueprint $table) {
            $table->string('nopol_kendaraan')->nullable()->after('status_perjalanan');
            $table->unsignedBigInteger('pengemudi_id')->nullable()->after('nopol_kendaraan');
            
            // Buat kembali foreign key constraints
            $table->foreign('nopol_kendaraan')->references('nopol_kendaraan')->on('kendaraans');
            $table->foreign('pengemudi_id')->references('staf_id')->on('stafs');
        });
    }
};