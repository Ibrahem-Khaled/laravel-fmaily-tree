<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class QuranCompetition extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'hijri_year',
        'start_date',
        'end_date',
        'cover_image',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * العلاقة مع الفائزين
     */
    public function winners()
    {
        return $this->hasMany(QuranCompetitionWinner::class, 'competition_id')->orderBy('position');
    }

    /**
     * العلاقة مع الوسائط
     */
    public function media()
    {
        return $this->hasMany(QuranCompetitionMedia::class, 'competition_id')->orderBy('sort_order');
    }

    /**
     * الحصول على رابط صورة الغلاف
     */
    public function getCoverImageUrlAttribute(): ?string
    {
        if ($this->cover_image) {
            return asset('storage/' . $this->cover_image);
        }
        return null;
    }

    /**
     * Scope للمسابقات النشطة
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope للمسابقات مرتبة
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('hijri_year', 'desc');
    }

    /**
     * حذف الصورة عند حذف المسابقة
     */
    protected static function booted(): void
    {
        static::deleting(function (QuranCompetition $competition) {
            if ($competition->cover_image) {
                Storage::disk('public')->delete($competition->cover_image);
            }
        });
    }
}

