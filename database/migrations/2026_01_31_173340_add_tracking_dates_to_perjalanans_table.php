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
            $table->timestamp('tgl_input_surat_tugas')->nullable()->after('no_surat_tugas');
            $table->timestamp('tgl_upload_surat_tugas')->nullable()->after('upload_surat_tugas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perjalanans', function (Blueprint $table) {
            $table->dropColumn(['tgl_input_surat_tugas', 'tgl_upload_surat_tugas']);
        });
    }
};
