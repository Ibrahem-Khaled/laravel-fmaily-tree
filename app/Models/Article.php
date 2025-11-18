<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends BaseModel
{
    use HasFactory;
    protected $fillable = [
        'title',
        'content',
        'category_id',
        'person_id',
        'status',
        'created_at'
    ];

    protected $attributes = [
        'status' => 'draft'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class)->orderBy('sort_order')->orderBy('id');
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function getFirstYouTubeIdAttribute(): ?string
    {
        $video = $this->videos()->first();
        return $video && $video->provider === 'youtube' ? $video->video_id : null;
    }

    // سكوبات
    public function scopeSearch($q, ?string $term)
    {
        if (!$term) return $q;
        return $q->where(function ($qq) use ($term) {
            $qq->where('title', 'like', "%$term%")
                ->orWhere('content', 'like', "%$term%");
        });
    }
    public function scopeStatus($q, ?string $status)
    {
        if (!$status || !in_array($status, ['published', 'draft'])) return $q;
        return $q->where('status', $status);
    }
    public function scopeInCategory($q, $categoryId)
    {
        if (!$categoryId) return $q;
        return $q->where('category_id', $categoryId);
    }
    // (اختياري) سكوب للفلترة بحسب الناشر
    public function scopeByPerson($q, $personId)
    {
        return $personId ? $q->where('person_id', $personId) : $q;
    }
}
