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
            $table->string('jabatan'); // e.g., 'Pengemudi', 'Asisten'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stafs');
    }
};
