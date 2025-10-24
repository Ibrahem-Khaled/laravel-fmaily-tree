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
        'thumbnail_path',
        'youtube_url',
        'media_type',
        'file_size',
        'file_extension',
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
            if ($image->thumbnail_path) {
                Storage::disk('public')->delete($image->thumbnail_path);
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

        $patterns = [
            '/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/',
            '/youtube\.com\/watch\?.*v=([a-zA-Z0-9_-]{11})/'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $this->youtube_url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * Check if this is a PDF file
     */
    public function isPdf(): bool
    {
        return $this->media_type === 'pdf' && !empty($this->path);
    }

    /**
     * Get file size in human readable format
     */
    public function getFormattedFileSize(): string
    {
        if (!$this->file_size) {
            return 'غير محدد';
        }

        $bytes = (int) $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get file extension
     */
    public function getFileExtension(): string
    {
        return $this->file_extension ?: pathinfo($this->path, PATHINFO_EXTENSION);
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

    /**
     * Get thumbnail URL - returns custom thumbnail if available, otherwise default
     */
    public function getThumbnailUrl(): string
    {
        // إذا كانت هناك صورة مصغرة مخصصة، استخدمها
        if ($this->thumbnail_path) {
            return asset('storage/' . $this->thumbnail_path);
        }

        // إذا كان فيديو يوتيوب، استخدم الصورة المصغرة الافتراضية
        if ($this->isYouTube()) {
            return $this->getYouTubeThumbnail();
        }

        // إذا كان ملف PDF، استخدم الصورة الافتراضية
        if ($this->isPdf()) {
            return asset('assets/img/base-pdf-img.jpg');
        }

        // إذا كانت صورة عادية، استخدم الصورة نفسها
        if ($this->path) {
            return asset('storage/' . $this->path);
        }

        // صورة افتراضية في حالة عدم وجود أي شيء
        return asset('assets/img/default-image.jpg');
    }

    /**
     * Check if has custom thumbnail
     */
    public function hasCustomThumbnail(): bool
    {
        return !empty($this->thumbnail_path);
    }
}
