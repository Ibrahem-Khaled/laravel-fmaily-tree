<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class QuranCompetitionMedia extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'competition_id',
        'media_type',
        'file_path',
        'youtube_url',
        'caption',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * العلاقة مع المسابقة
     */
    public function competition()
    {
        return $this->belongsTo(QuranCompetition::class, 'competition_id');
    }

    /**
     * الحصول على رابط الملف
     */
    public function getFileUrlAttribute(): ?string
    {
        if ($this->file_path) {
            return asset('storage/' . $this->file_path);
        }
        return null;
    }

    /**
     * الحصول على رابط فيديو يوتيوب
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
    private function extractVideoId($url): ?string
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
        static::deleting(function (QuranCompetitionMedia $media) {
            if ($media->file_path) {
                Storage::disk('public')->delete($media->file_path);
            }
        });
    }
}

