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
        Schema::create('pengajuan_prs', function (Blueprint $table) {
            $table->id();
            $table->text('nama_perkerjaan')->nullable();
            $table->date('tanggal_usulan')->default(now());
            $table->decimal('total', 15, 2)->nullable();
            $table->json('upload_files')->nullable();
            $table->string('nomor_pr')->nullable();
            $table->json('proses_pr_screenshots')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_prs');
    }
};
