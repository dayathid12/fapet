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
        Schema::table('perjalanan_kendaraans', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['pengemudi_id']);
            // Make the column nullable and re-add the foreign key
            $table->unsignedBigInteger('pengemudi_id')->nullable()->change();
            $table->foreign('pengemudi_id')->references('staf_id')->on('stafs')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perjalanan_kendaraans', function (Blueprint $table) {
            // Drop the nullable foreign key
            $table->dropForeign(['pengemudi_id']);
            // Make the column non-nullable and re-add the foreign key
            $table->unsignedBigInteger('pengemudi_id')->change();
            $table->foreign('pengemudi_id')->references('staf_id')->on('stafs')->cascadeOnDelete();
        });
    }
};
