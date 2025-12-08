<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImportantLink extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'title',
        'url',
        'icon',
        'description',
        'order',
        'is_active',
        'open_in_new_tab'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'open_in_new_tab' => 'boolean',
        'order' => 'integer'
    ];

    /**
     * جلب الروابط النشطة مرتبة
     */
    public static function getActiveLinks()
    {
        return self::where('is_active', true)
            ->orderBy('order')
            ->get();
    }
}
