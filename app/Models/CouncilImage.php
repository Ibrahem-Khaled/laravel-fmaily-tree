<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class CouncilImage extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'council_id',
        'image_path',
        'thumbnail_path',
        'description',
        'display_order',
    ];

    /**
     * المجلس الذي تنتمي إليه الصورة
     */
    public function council(): BelongsTo
    {
        return $this->belongsTo(FamilyCouncil::class, 'council_id');
    }

    /**
     * الحصول على رابط الصورة
     */
    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image_path);
    }

    /**
     * الحصول على رابط الصورة المصغرة
     */
    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail_path) {
            return asset('storage/' . $this->thumbnail_path);
        }
        return $this->image_url;
    }

    protected static function booted(): void
    {
        static::deleting(function (CouncilImage $image) {
            if ($image->image_path) {
                Storage::disk('public')->delete($image->image_path);
            }
            if ($image->thumbnail_path) {
                Storage::disk('public')->delete($image->thumbnail_path);
            }
        });
    }
}
