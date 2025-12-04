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
            // To make a foreign key nullable in SQLite,
            // we first need to drop the existing foreign key constraint (if any)
            // and then re-add the column with the nullable attribute and foreign key.
            // SQLite does not support dropping foreign keys or changing column types with existing constraints directly.
            // A common workaround is to recreate the table, but that's complex for a migration.
            // For development, simply changing the column to nullable without explicitly
            // dropping and re-adding the foreign key might work implicitly via Laravel's grammar.
            $table->unsignedBigInteger('pengemudi_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perjalanans', function (Blueprint $table) {
            // Revert to non-nullable.
            $table->unsignedBigInteger('pengemudi_id')->change();
        });
    }
};
