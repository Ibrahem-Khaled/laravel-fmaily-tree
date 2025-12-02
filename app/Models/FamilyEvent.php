<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class FamilyEvent extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'city',
        'location',
        'event_date',
        'show_countdown',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'show_countdown' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * جلب الأحداث النشطة مرتبة
     */
    public static function getActiveEvents()
    {
        return self::where('is_active', true)
            ->orderBy('display_order')
            ->orderBy('event_date')
            ->get();
    }
}

