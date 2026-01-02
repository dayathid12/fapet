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
                    $table->dropUnique(['no_surat_tugas']);
                    $table->string('no_surat_tugas')->nullable()->change();
                });
            }
    /**
     * Reverse the migrations.
     */
            public function down(): void
            {
                Schema::table('perjalanans', function (Blueprint $table) {
                    $table->string('no_surat_tugas')->unique()->change();
                });
            }};
