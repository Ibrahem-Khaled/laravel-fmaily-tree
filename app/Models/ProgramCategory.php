<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramCategory extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (ProgramCategory $category) {
            if ($category->sort_order === null) {
                $max = static::max('sort_order');
                $category->sort_order = $max === null ? 0 : (int) $max + 1;
            }
        });
    }

    /**
     * البرامج الرئيسية (صور العرض في الصفحة الرئيسية) المرتبطة بهذه الفئة.
     */
    public function programs(): HasMany
    {
        return $this->hasMany(Image::class, 'program_category_id')
            ->where('is_program', true)
            ->whereNull('program_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
