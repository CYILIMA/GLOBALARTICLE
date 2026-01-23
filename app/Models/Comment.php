<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'news_id',
        'author_name',
        'author_email',
        'content',
        'status',
        'ip_address',
        'flagged',
        'flag_reason',
    ];

    protected $casts = [
        'flagged' => 'boolean',
    ];

    // Relationships
    public function news()
    {
        return $this->belongsTo(News::class);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFlagged($query)
    {
        return $query->where('flagged', true);
    }

    // Helper methods
    public function approve()
    {
        $this->update(['status' => 'approved', 'flagged' => false]);
    }

    public function reject()
    {
        $this->update(['status' => 'rejected']);
    }

    public function flag($reason = null)
    {
        $this->update(['flagged' => true, 'flag_reason' => $reason]);
    }
}
