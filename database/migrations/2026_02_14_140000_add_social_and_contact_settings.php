<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Insert new settings
        DB::table('settings')->insert([
            // Social Media
            ['key' => 'social_facebook', 'value' => 'https://facebook.com', 'type' => 'string', 'group' => 'social', 'description' => 'Facebook URL', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'social_x', 'value' => 'https://x.com', 'type' => 'string', 'group' => 'social', 'description' => 'X (Twitter) URL', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'social_instagram', 'value' => 'https://instagram.com', 'type' => 'string', 'group' => 'social', 'description' => 'Instagram URL', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'social_youtube', 'value' => 'https://youtube.com', 'type' => 'string', 'group' => 'social', 'description' => 'YouTube URL', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'social_linkedin', 'value' => 'https://linkedin.com', 'type' => 'string', 'group' => 'social', 'description' => 'LinkedIn URL', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'social_whatsapp', 'value' => 'https://whatsapp.com', 'type' => 'string', 'group' => 'social', 'description' => 'WhatsApp URL', 'created_at' => now(), 'updated_at' => now()],

            // Contact Info
            ['key' => 'contact_email', 'value' => 'contact@globalarticles.com', 'type' => 'string', 'group' => 'contact', 'description' => 'Contact Email Address', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'contact_phone', 'value' => '+250796306573', 'type' => 'string', 'group' => 'contact', 'description' => 'Contact Phone Number', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'contact_address', 'value' => 'Kigali, Rwanda', 'type' => 'string', 'group' => 'contact', 'description' => 'Physical Address', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'social_facebook', 'social_x', 'social_instagram', 'social_youtube', 'social_linkedin', 'social_whatsapp',
            'contact_email', 'contact_phone', 'contact_address'
        ])->delete();
    }
};
