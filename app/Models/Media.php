<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = ['name', 'file_path', 'mime_type', 'size', 'folder_id', 'disk'];

    public function folder()
    {
        return $this->belongsTo(MediaFolder::class, 'folder_id');
    }

    public function getUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }
}
