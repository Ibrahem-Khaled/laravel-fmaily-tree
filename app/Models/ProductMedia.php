<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class ProductMedia extends BaseModel
{
    use HasFactory;

    protected $table = 'product_media';

    protected $fillable = [
        'product_id',
        'media_type',
        'file_path',
        'youtube_url',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * العلاقة مع المنتج
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * الحصول على رابط الملف
     */
    public function getUrlAttribute(): ?string
    {
        if ($this->media_type === 'youtube') {
            return $this->youtube_url;
        }
        
        if ($this->file_path) {
            return asset('storage/' . $this->file_path);
        }
        
        return null;
    }

    /**
     * الحصول على صورة مصغرة لفيديو يوتيوب
     */
    public function getYouTubeThumbnailAttribute(): ?string
    {
        if ($this->media_type === 'youtube' && $this->youtube_url) {
            $videoId = $this->extractVideoId($this->youtube_url);
            if ($videoId) {
                return "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg";
            }
        }
        return null;
    }

    /**
     * استخراج معرف فيديو يوتيوب من الرابط
     */
    public function extractVideoId($url): ?string
    {
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1] ?? null;
        }
        return null;
    }

    /**
     * حذف الملف عند حذف الوسائط
     */
    protected static function booted(): void
    {
        static::deleting(function (ProductMedia $media) {
            if ($media->file_path) {
                Storage::disk('public')->delete($media->file_path);
            }
        });
    }
}
