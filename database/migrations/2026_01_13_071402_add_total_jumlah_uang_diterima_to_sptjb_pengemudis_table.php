<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sptjb_pengemudis', function (Blueprint $table) {
            $table->bigInteger('total_jumlah_uang_diterima')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('sptjb_pengemudis', function (Blueprint $table) {
            $table->dropColumn('total_jumlah_uang_diterima');
        });
    }
};
