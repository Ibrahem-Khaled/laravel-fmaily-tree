<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class HomeGalleryImage extends BaseModel
{
    use HasFactory;
    
    protected $fillable = [
        'image_path',
        'name',
        'category_id',
        'order',
        'is_active'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer'
    ];
    
    /**
     * العلاقة مع الفئة (اختيارية)
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
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
     * جلب الصور النشطة مرتبة
     */
    public static function getActiveGalleryImages()
    {
        return self::where('is_active', true)
            ->with('category:id,name')
            ->orderBy('order')
            ->take(8)
            ->get();
    }
    
    /**
     * حذف الصورة عند حذف السجل
     */
    protected static function booted(): void
    {
        static::deleting(function (HomeGalleryImage $galleryImage) {
            if ($galleryImage->image_path) {
                Storage::disk('public')->delete($galleryImage->image_path);
            }
        });
    }
}

