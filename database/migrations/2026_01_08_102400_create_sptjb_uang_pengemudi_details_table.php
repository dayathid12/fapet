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
        Schema::create('sptjb_uang_pengemudi_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sptjb_pengemudi_id')->constrained('sptjb_pengemudis')->onDelete('cascade');
            $table->integer('no')->nullable(); // Auto-generated based on row
            $table->string('nama');
            $table->string('jabatan');
            $table->date('tanggal_penugasan');
            $table->integer('jumlah_hari');
            $table->decimal('besaran_uang_per_hari', 15, 2);
            $table->decimal('jumlah_rp', 15, 2);
            $table->decimal('jumlah_uang_diterima', 15, 2);
            $table->string('nomor_rekening');
            $table->string('golongan');
            $table->string('no_sptjb')->nullable(); // Auto-filled from parent
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sptjb_uang_pengemudi_details');
    }
};
