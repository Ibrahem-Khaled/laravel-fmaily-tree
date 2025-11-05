<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Course extends BaseModel
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'description',
        'image_path',
        'instructor',
        'duration',
        'students',
        'link',
        'order',
        'is_active'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'students' => 'integer'
    ];
    
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
     * جلب الدورات النشطة مرتبة
     */
    public static function getActiveCourses()
    {
        return self::where('is_active', true)
            ->orderBy('order')
            ->get();
    }
    
    /**
     * حذف الصورة عند حذف الدورة
     */
    protected static function booted(): void
    {
        static::deleting(function (Course $course) {
            if ($course->image_path) {
                Storage::disk('public')->delete($course->image_path);
            }
        });
    }
}
