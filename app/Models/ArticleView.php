<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleView extends Model
{
    protected $fillable = ['news_id', 'ip_address', 'user_agent'];

    public function article()
    {
        return $this->belongsTo(News::class, 'news_id');
    }
}
