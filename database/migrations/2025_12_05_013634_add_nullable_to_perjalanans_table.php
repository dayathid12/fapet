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
        Schema::table('perjalanans', function (Blueprint $table) {
            $table->foreignId('pengemudi_id')->nullable()->change();
            $table->string('nopol_kendaraan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perjalanans', function (Blueprint $table) {
            // Revert to non-nullable if necessary, but generally not recommended
            // to revert non-nullable changes if data might already exist.
            // For this specific case, we can assume it's fine to revert.
            $table->foreignId('pengemudi_id')->nullable(false)->change();
            $table->string('nopol_kendaraan')->nullable(false)->change();
        });
    }
};