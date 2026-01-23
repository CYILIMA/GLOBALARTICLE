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
            $table->string('status')->default('draft')->after('breaking'); // draft, published, scheduled, archived
            $table->timestamp('published_at')->nullable()->after('status');
            $table->timestamp('scheduled_at')->nullable()->after('published_at');
            $table->string('meta_title')->nullable()->after('scheduled_at');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->unsignedInteger('views')->default(0)->after('meta_description');
            // Assuming we might link to categories table later, but for now we have 'category' string.
            // Let's add category_id as nullable for future transition
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete()->after('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['status', 'published_at', 'scheduled_at', 'meta_title', 'meta_description', 'views', 'category_id']);
        });
    }
};
