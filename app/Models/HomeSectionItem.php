<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class HomeSectionItem extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'home_section_id',
        'item_type',
        'content',
        'media_path',
        'youtube_url',
        'display_order',
        'settings',
    ];

    protected $casts = [
        'display_order' => 'integer',
        'content' => 'array',
        'settings' => 'array',
    ];

    /**
     * علاقة القسم
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(HomeSection::class);
    }

    /**
     * الحصول على رابط الصورة
     */
    public function getImageUrlAttribute(): ?string
    {
        if ($this->media_path) {
            if (filter_var($this->media_path, FILTER_VALIDATE_URL)) {
                return $this->media_path;
            }
            return asset('storage/' . $this->media_path);
        }
        return null;
    }

    /**
     * الحصول على رابط الفيديو
     */
    public function getVideoUrlAttribute(): ?string
    {
        if ($this->youtube_url) {
            return $this->youtube_url;
        }
        if ($this->media_path) {
            if (filter_var($this->media_path, FILTER_VALIDATE_URL)) {
                return $this->media_path;
            }
            return asset('storage/' . $this->media_path);
        }
        return null;
    }

    /**
     * إعادة ترتيب العناصر
     */
    public static function reorder(int $sectionId, array $orderIds)
    {
        foreach ($orderIds as $index => $id) {
            self::where('id', $id)
                ->where('home_section_id', $sectionId)
                ->update(['display_order' => $index + 1]);
        }
    }
}
