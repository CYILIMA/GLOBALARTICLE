<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('login_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('ip_address', 45);
            $table->text('user_agent');
            $table->string('device_type')->nullable(); // desktop, mobile, tablet
            $table->string('browser')->nullable();
            $table->boolean('successful')->default(true);
            $table->timestamp('login_at');
            $table->timestamps();

            $table->index(['user_id', 'login_at']);
            $table->index('ip_address');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_history');
    }
};
