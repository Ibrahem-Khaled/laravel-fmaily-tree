<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HomeSection extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'title',
        'section_type',
        'display_order',
        'is_active',
        'settings',
        'css_classes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
        'settings' => 'array',
    ];

    /**
     * علاقة العناصر داخل القسم
     */
    public function items(): HasMany
    {
        return $this->hasMany(HomeSectionItem::class)->orderBy('display_order');
    }

    /**
     * جلب الأقسام النشطة مرتبة
     */
    public static function getActiveSections()
    {
        return self::where('is_active', true)
            ->orderBy('display_order')
            ->with('items')
            ->get();
    }

    /**
     * إعادة ترتيب الأقسام
     */
    public static function reorder(array $orderIds)
    {
        foreach ($orderIds as $index => $id) {
            self::where('id', $id)->update(['display_order' => $index + 1]);
        }
    }

    /**
     * تفعيل/تعطيل قسم
     */
    public function toggle()
    {
        $this->is_active = !$this->is_active;
        $this->save();
        return $this->is_active;
    }
}
