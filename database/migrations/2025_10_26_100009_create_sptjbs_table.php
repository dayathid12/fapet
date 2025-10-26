<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sptjbs', function (Blueprint $table) {
            $table->string('no_sptjb')->primary();
            $table->foreignId('nomor_perjalanan')->constrained('perjalanans', 'nomor_perjalanan');
            $table->text('uraian')->nullable();
            $table->string('penerima')->nullable();
            $table->string('file_document')->nullable();
            $table->string('file_sptjb')->nullable();
            $table->string('daftar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sptjbs');
    }
};
