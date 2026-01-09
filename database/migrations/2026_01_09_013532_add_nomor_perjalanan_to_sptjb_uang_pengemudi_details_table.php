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
            $table->string('nomor_perjalanan')->nullable()->after('no_sptjb');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sptjb_uang_pengemudi_details', function (Blueprint $table) {
            $table->dropColumn('nomor_perjalanan');
        });
    }
};
