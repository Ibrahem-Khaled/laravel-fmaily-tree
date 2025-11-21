<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Category extends BaseModel
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'image',
        'parent_id',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        // استخدام الحدث 'creating' الذي يتم إطلاقه قبل إنشاء سجل جديد
        static::creating(function ($category) {
            // التحقق إذا كان حقل sort_order لم يتم تعيينه بالفعل
            if (is_null($category->sort_order)) {
                // البحث عن أعلى قيمة لـ sort_order في نفس المستوى (نفس parent_id)
                $maxSortOrder = self::where('parent_id', $category->parent_id)->max('sort_order');

                // تعيين القيمة الجديدة لتكون +1 من أعلى قيمة موجودة
                // إذا لم يكن هناك فئات بعد (maxSortOrder is null)، فستكون القيمة 0
                $category->sort_order = ($maxSortOrder === null) ? 0 : $maxSortOrder + 1;
            }
        });
    }

    // العلاقات
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    // سكوبات
    public function scopeRoots($q)
    {
        return $q->whereNull('parent_id');
    }
    public function scopeOrdered($q)
    {
        return $q->orderBy('sort_order')->orderBy('name');
    }
    public function scopeHasArticles($query)
    {
        return $query->whereHas('articles');
    }


    public function scopeNoArticles($query)
    {
        return $query->whereDoesntHave('articles');
    }


    public function scopeHasImages($query)
    {
        return $query->whereHas('images');
    }


    public function scopeNoImages($query)
    {
        return $query->whereDoesntHave('images');
    }


    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }


    public function scopeChildren($query)
    {
        return $query->whereNotNull('parent_id');
    }


    public function scopeSearch($query, ?string $term)
    {
        if (!$term) return $query;
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%");
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }
}
