<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentaries', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->longText('content');
            $table->string('thumbnail')->nullable();
            $table->string('video_url')->nullable();
            $table->string('status')->default('draft'); // draft, published
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();

            $table->index('status');
            $table->index('published_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentaries');
    }
};
