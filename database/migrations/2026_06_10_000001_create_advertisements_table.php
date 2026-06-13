<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->string('advertiser_name');
            $table->string('banner_title');
            $table->string('banner_image');
            $table->string('target_url', 500);
            $table->enum('position', ['header_leaderboard', 'sidebar_rectangle', 'in_article', 'footer_banner'])->default('header_leaderboard');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['active', 'inactive', 'scheduled', 'expired'])->default('active');
            $table->unsignedBigInteger('impressions')->default(0);
            $table->unsignedBigInteger('clicks')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};
