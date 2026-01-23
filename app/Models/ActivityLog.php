<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'model',
        'model_id',
        'description',
        'changes',
        'ip_address',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper method to log activity
    public static function log($action, $model, $modelId = null, $description = null, $changes = null)
    {
        return self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model' => $model,
            'model_id' => $modelId,
            'description' => $description,
            'changes' => $changes,
            'ip_address' => request()->ip(),
        ]);
    }

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByModel($query, $model)
    {
        return $query->where('model', $model);
    }

    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }
}
