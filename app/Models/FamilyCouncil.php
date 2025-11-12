<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FamilyCouncil extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'address',
        'google_map_url',
        'working_days_from',
        'working_days_to',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * صور المجلس
     */
    public function images(): HasMany
    {
        return $this->hasMany(CouncilImage::class, 'council_id')->orderBy('display_order');
    }

    /**
     * جلب المجالس النشطة مرتبة
     */
    public static function getActiveCouncils()
    {
        return self::where('is_active', true)
            ->orderBy('display_order')
            ->get();
    }
}
