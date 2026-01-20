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
            $table->renameColumn('nomor_perjalanan', 'nomor_surat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sptjb_uang_pengemudi_details', function (Blueprint $table) {
            $table->renameColumn('nomor_surat', 'nomor_perjalanan');
        });
    }
};
