<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Image extends BaseModel
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'path',
        'youtube_url',
        'media_type',
        'article_id',
        'category_id'
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function mentionedPersons(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'image_mentions', 'image_id', 'person_id')
            ->withTimestamps()
            ->withPivot('order')
            ->orderBy('image_mentions.order');
    }

    protected static function booted(): void
    {
        static::deleting(function (Image $image) {
            if ($image->path) {
                Storage::disk('public')->delete($image->path);
            }
        });
    }

    /**
     * Check if this is a YouTube video
     */
    public function isYouTube(): bool
    {
        return $this->media_type === 'youtube' && !empty($this->youtube_url);
    }

    /**
     * Get YouTube video ID from URL
     */
    public function getYouTubeId(): ?string
    {
        if (!$this->isYouTube()) {
            return null;
        }

        $url = $this->youtube_url;

        // Extract video ID from various YouTube URL formats
        $patterns = [
            '/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/',
            '/youtube\.com\/watch\?.*v=([a-zA-Z0-9_-]{11})/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * Get YouTube thumbnail URL
     */
    public function getYouTubeThumbnail(): ?string
    {
        $videoId = $this->getYouTubeId();
        if (!$videoId) {
            return null;
        }

        return "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg";
    }

    /**
     * Get YouTube embed URL
     */
    public function getYouTubeEmbedUrl(): ?string
    {
        $videoId = $this->getYouTubeId();
        if (!$videoId) {
            return null;
        }

        return "https://www.youtube.com/embed/{$videoId}";
    }
}
