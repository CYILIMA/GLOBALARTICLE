<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comment_filters', function (Blueprint $table) {
            $table->id();
            $table->string('keyword');
            $table->string('action')->default('flag'); // flag, reject
            $table->timestamps();

            $table->index('keyword');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comment_filters');
    }
};
