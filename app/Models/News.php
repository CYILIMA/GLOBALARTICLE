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
        'category', // Legacy string
        'category_id', // New relation
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
    ];

    public function categoryData()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
