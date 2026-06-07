<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ImportantLinkCategory extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (ImportantLinkCategory $category) {
            if ($category->sort_order === null) {
                $max = static::max('sort_order');
                $category->sort_order = $max === null ? 0 : (int) $max + 1;
            }
        });
    }

    /**
     * الروابط التابعة لهذه الفئة
     */
    public function links(): HasMany
    {
        return $this->hasMany(ImportantLink::class, 'category_id')->orderBy('order');
    }

    /**
     * الروابط التابعة والنشطة والمعتمدة لعرضها في الموقع العام
     */
    public function activeLinks(): HasMany
    {
        return $this->hasMany(ImportantLink::class, 'category_id')
            ->where('is_active', true)
            ->where('status', 'approved')
            ->orderBy('order');
    }
}
