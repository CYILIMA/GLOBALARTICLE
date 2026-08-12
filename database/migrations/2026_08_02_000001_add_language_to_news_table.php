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
        if (Schema::hasColumn('news', 'language')) {
            return;
        }

        Schema::table('news', function (Blueprint $table) {
            $table->enum('language', ['english', 'kinyarwanda', 'french'])->default('english')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * This migration may be recorded on databases where the column was
     * created before the migration was deployed, so it must not remove data
     * that predates the migration.
     */
    public function down(): void
    {
        // Intentionally left in place for safe rollbacks.
    }
};
