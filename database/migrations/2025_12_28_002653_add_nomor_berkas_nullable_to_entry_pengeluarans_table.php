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
        Schema::table('entry_pengeluarans', function (Blueprint $table) {
            $table->dropUnique(['nomor_berkas']);
            $table->string('nomor_berkas')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entry_pengeluarans', function (Blueprint $table) {
            $table->string('nomor_berkas')->unique()->nullable(false)->change();
        });
    }
};
