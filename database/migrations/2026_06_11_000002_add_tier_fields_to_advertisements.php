<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->enum('display_type', ['premium', 'standard'])->default('standard')->after('status');
            $table->decimal('price', 10, 2)->nullable()->after('display_type');
        });
    }

    public function down(): void
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->dropColumn(['display_type', 'price']);
        });
    }
};
