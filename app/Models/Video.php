<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'provider',
        'video_id',
        'url',
        'title',
        'sort_order',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}


