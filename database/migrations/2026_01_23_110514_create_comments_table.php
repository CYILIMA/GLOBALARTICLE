<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_id')->constrained()->onDelete('cascade');
            $table->string('author_name');
            $table->string('author_email');
            $table->text('content');
            $table->string('status')->default('pending'); // pending, approved, rejected, flagged
            $table->string('ip_address', 45);
            $table->boolean('flagged')->default(false);
            $table->text('flag_reason')->nullable();
            $table->timestamps();

            $table->index(['news_id', 'status']);
            $table->index('status');
            $table->index('ip_address');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
