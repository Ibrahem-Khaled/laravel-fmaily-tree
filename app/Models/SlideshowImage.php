<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class SlideshowImage extends BaseModel
{
    use HasFactory;
    
    protected $fillable = [
        'image_path',
        'image_id',
        'title',
        'description',
        'link',
        'order',
        'is_active'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer'
    ];
    
    /**
     * العلاقة مع الصورة (اختيارية)
     */
    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }
    
    /**
     * الحصول على رابط الصورة
     */
    public function getImageUrlAttribute(): ?string
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }
        
        if ($this->image && $this->image->path) {
            return asset('storage/' . $this->image->path);
        }
        
        return null;
    }
    
    /**
     * جلب الصور النشطة مرتبة
     */
    public static function getActiveSlideshowImages()
    {
        return self::where('is_active', true)
            ->orderBy('order')
            ->get();
    }
    
    /**
     * حذف الصورة عند حذف السلايدشو
     */
    protected static function booted(): void
    {
        static::deleting(function (SlideshowImage $slideshowImage) {
            if ($slideshowImage->image_path) {
                Storage::disk('public')->delete($slideshowImage->image_path);
            }
        });
    }
}
