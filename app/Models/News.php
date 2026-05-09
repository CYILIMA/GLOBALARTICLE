<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'content_blocks', // JSON block data
        'content_version', // v1=HTML, v2=blocks
        'category', // Legacy string
        'category_id', // New relation
        'user_id', // Author relation
        'image',
        'image_source',
        'breaking',
        'status',
        'published_at',
        'scheduled_at',
        'breaking_expires_at',
        'meta_title',
        'meta_description',
        'views'
    ];

    protected $casts = [
        'breaking' => 'boolean',
        'published_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'breaking_expires_at' => 'datetime',
        'content_blocks' => 'array', // Cast JSON to array
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function categoryData()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Accessor to strip trailing mock numbers like " (1)" from titles.
     */
    public function getTitleAttribute($value)
    {
        return preg_replace('/\s\(\d+\)$/', '', $value);
    }

    // Laravel uses this column instead of `id` for route model binding
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Generate a unique slug, optionally excluding a given news id (for updates)
    public static function generateSlug(string $title, ?int $excludeId = null): string
    {
        $base  = Str::slug($title) ?: 'article';
        $slug  = $base;
        $count = 2;

        while (
            static::where('slug', $slug)
                ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                ->exists()
        ) {
            $slug = $base . '-' . $count++;
        }

        return $slug;
    }
}
