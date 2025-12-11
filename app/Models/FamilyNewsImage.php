<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class FamilyNewsImage extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'family_news_id',
        'image_path',
        'caption',
        'display_order',
    ];

    protected $casts = [
        'display_order' => 'integer',
    ];

    /**
     * علاقة الخبر
     */
    public function news()
    {
        return $this->belongsTo(FamilyNews::class, 'family_news_id');
    }

    /**
     * الحصول على رابط الصورة
     */
    public function getImageUrlAttribute(): ?string
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }

        return null;
    }

    /**
     * حذف الصورة عند حذف السجل
     */
    protected static function booted(): void
    {
        static::deleting(function (FamilyNewsImage $image) {
            if ($image->image_path) {
                Storage::disk('public')->delete($image->image_path);
            }
        });
    }
}
