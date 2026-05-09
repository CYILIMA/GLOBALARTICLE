<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // Add nullable first so existing rows don't violate the constraint
        Schema::table('news', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
        });

        // Populate slugs for every existing row
        $seen = [];
        DB::table('news')->orderBy('id')->each(function ($row) use (&$seen) {
            // Strip mock-number suffix that the title accessor also removes
            $clean = preg_replace('/\s\(\d+\)$/', '', $row->title ?? '');
            $base  = Str::slug($clean) ?: 'article';

            $slug  = $base;
            $i     = 2;
            while (in_array($slug, $seen)) {
                $slug = $base . '-' . $i++;
            }
            $seen[] = $slug;

            DB::table('news')->where('id', $row->id)->update(['slug' => $slug]);
        });

        // Now it is safe to enforce uniqueness
        Schema::table('news', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
