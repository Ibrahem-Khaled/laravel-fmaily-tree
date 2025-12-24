<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuranCategoryManager extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'person_id',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * العلاقة مع الفئة
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * العلاقة مع الشخص
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}
