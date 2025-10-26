<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biaya_lains', function (Blueprint $table) {
            $table->id('biaya_lain_id');
            $table->foreignId('nomor_perjalanan')->constrained('perjalanans', 'nomor_perjalanan');
            $table->string('uraian_biaya');
            $table->decimal('biaya', 15, 2);
            $table->string('gambar_bukti')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biaya_lains');
    }
};
