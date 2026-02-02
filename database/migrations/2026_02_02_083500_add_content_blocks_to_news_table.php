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
        Schema::table('news', function (Blueprint $table) {
            // Add JSON column for block-based content
            $table->json('content_blocks')->nullable()->after('content');
            
            // Add version column to track content format
            $table->string('content_version', 10)->default('v1')->after('content_blocks');
            // v1 = HTML (legacy), v2 = blocks (new)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn(['content_blocks', 'content_version']);
        });
    }
};
