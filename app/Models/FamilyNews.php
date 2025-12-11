<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class FamilyNews extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'summary',
        'main_image_path',
        'published_at',
        'display_order',
        'is_active',
        'views_count',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_active' => 'boolean',
        'display_order' => 'integer',
        'views_count' => 'integer',
    ];

    /**
     * علاقة الصور الفرعية
     */
    public function images()
    {
        return $this->hasMany(FamilyNewsImage::class)->orderBy('display_order');
    }

    /**
     * الحصول على رابط الصورة الرئيسية
     */
    public function getMainImageUrlAttribute(): ?string
    {
        if ($this->main_image_path) {
            return asset('storage/' . $this->main_image_path);
        }

        return null;
    }

    /**
     * جلب الأخبار النشطة مرتبة
     */
    public static function getActiveNews($limit = null)
    {
        $query = self::where('is_active', true)
            ->where(function($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now());
            })
            ->orderBy('display_order')
            ->orderBy('published_at', 'desc');

        if ($limit) {
            return $query->take($limit)->get();
        }

        return $query->get();
    }

    /**
     * زيادة عدد المشاهدات
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * حذف الصورة الرئيسية عند حذف الخبر
     */
    protected static function booted(): void
    {
        static::deleting(function (FamilyNews $news) {
            if ($news->main_image_path) {
                Storage::disk('public')->delete($news->main_image_path);
            }
        });
    }
}
