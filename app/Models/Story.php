<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Story extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'title',
        'story_owner_id',
        'content',
        'audio_path',
        'video_url',
        'video_path',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * صاحب القصة
     */
    public function storyOwner(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'story_owner_id');
    }

    /**
     * الرواة (الذين روا القصة)
     * علاقة many-to-many
     */
    public function narrators(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'story_narrators')
            ->withTimestamps();
    }

    /**
     * التحقق من وجود محتوى نصي
     */
    public function hasContent(): bool
    {
        return !empty($this->content);
    }

    /**
     * التحقق من وجود ملف صوتي
     */
    public function hasAudio(): bool
    {
        return !empty($this->audio_path);
    }

    /**
     * التحقق من وجود فيديو خارجي (يوتيوب)
     */
    public function hasExternalVideo(): bool
    {
        return !empty($this->video_url);
    }

    /**
     * التحقق من وجود ملف فيديو مرفوع
     */
    public function hasUploadedVideo(): bool
    {
        return !empty($this->video_path);
    }

    /**
     * التحقق من وجود أي فيديو (خارجي أو مرفوع)
     */
    public function hasVideo(): bool
    {
        return $this->hasExternalVideo() || $this->hasUploadedVideo();
    }

    /**
     * استخراج معرف فيديو يوتيوب من الرابط
     */
    public function getYoutubeVideoId(): ?string
    {
        if (!$this->video_url) {
            return null;
        }

        $patterns = [
            '/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/',
            '/youtube\.com\/watch\?.*v=([a-zA-Z0-9_-]{11})/'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $this->video_url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * الحصول على URL الملف الصوتي
     */
    public function getAudioUrl(): ?string
    {
        if (!$this->audio_path) {
            return null;
        }

        return asset('storage/' . $this->audio_path);
    }

    /**
     * الحصول على URL ملف الفيديو المرفوع
     */
    public function getUploadedVideoUrl(): ?string
    {
        if (!$this->video_path) {
            return null;
        }

        return asset('storage/' . $this->video_path);
    }
}
