<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Documentary extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'content',
        'thumbnail',
        'video_url',
        'status',
        'published_at',
        'views',
    ];

    public function categoryData()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    // Helper methods
    public function incrementViews()
    {
        $this->increment('views');
    }

    // Relationships
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isPublished()
    {
        return $this->status === 'published' && 
               $this->published_at && 
               $this->published_at->isPast();
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }
}
