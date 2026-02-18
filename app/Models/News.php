<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'content_blocks', // JSON block data
        'content_version', // v1=HTML, v2=blocks
        'category', // Legacy string
        'category_id', // New relation
        'user_id', // Author relation
        'image',
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
}
