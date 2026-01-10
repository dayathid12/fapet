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
            $table->string('tanggal_penugasan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sptjb_uang_pengemudi_details', function (Blueprint $table) {
            // Reverting may not be perfect if data is lost
            $table->date('tanggal_penugasan')->nullable()->change();
        });
    }
};