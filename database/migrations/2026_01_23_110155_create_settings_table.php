<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, integer, boolean, json
            $table->string('group')->default('general'); // general, email, content, advanced
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('group');
        });

        // Insert default settings
        DB::table('settings')->insert([
            ['key' => 'site_name', 'value' => 'Global Breakings', 'type' => 'string', 'group' => 'general', 'description' => 'Website name', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'site_language', 'value' => 'en', 'type' => 'string', 'group' => 'general', 'description' => 'Default language', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'timezone', 'value' => 'UTC', 'type' => 'string', 'group' => 'general', 'description' => 'System timezone', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'items_per_page', 'value' => '20', 'type' => 'integer', 'group' => 'general', 'description' => 'Items per page in listings', 'created_at' => now(), 'updated_at' => now()],

            ['key' => 'mail_from_address', 'value' => 'noreply@globalarticles.com', 'type' => 'string', 'group' => 'email', 'description' => 'From email address', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'mail_from_name', 'value' => 'Global Breakings', 'type' => 'string', 'group' => 'email', 'description' => 'From name', 'created_at' => now(), 'updated_at' => now()],

            ['key' => 'max_title_length', 'value' => '200', 'type' => 'integer', 'group' => 'content', 'description' => 'Maximum article title length', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'max_image_size', 'value' => '2048', 'type' => 'integer', 'group' => 'content', 'description' => 'Maximum image size in KB', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'enable_comments', 'value' => '1', 'type' => 'boolean', 'group' => 'content', 'description' => 'Enable article comments', 'created_at' => now(), 'updated_at' => now()],

            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean', 'group' => 'advanced', 'description' => 'Enable maintenance mode', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'cache_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'advanced', 'description' => 'Enable caching', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
