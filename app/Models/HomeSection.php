<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

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
        'content_source_type',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
        'settings' => 'array',
    ];

    /**
     * أنواع المصادر المسموح بها
     */
    public const ALLOWED_SOURCES = [
        'App\\Models\\User' => 'مستخدمين',
        'App\\Models\\Person' => 'أشخاص',
    ];

    /**
     * علاقة العناصر داخل القسم
     */
    public function items(): HasMany
    {
        return $this->hasMany(HomeSectionItem::class)->orderBy('display_order');
    }

    /**
     * جلب مجموعة من النموذج المصدر (إذا كان content_source_type محدداً)
     */
    public function getContentSourceCollection(): ?Collection
    {
        if (!$this->content_source_type || !class_exists($this->content_source_type)) {
            return null;
        }

        if (!array_key_exists($this->content_source_type, self::ALLOWED_SOURCES)) {
            return null;
        }

        $settings = $this->settings ?? [];
        $sourceMode = $settings['source_mode'] ?? 'all';
        $sourceIds = $settings['source_ids'] ?? [];

        // وضع "مختار" — عرض عناصر محددة فقط
        if ($sourceMode === 'selected' && !empty($sourceIds)) {
            $ids = array_map('intval', (array) $sourceIds);
            return $this->content_source_type::whereIn('id', $ids)
                ->orderByRaw('FIELD(id, ' . implode(',', $ids) . ')')
                ->get();
        }

        // وضع "الكل" — جلب حسب الترتيب والحد
        $limit = (int) ($settings['source_limit'] ?? 10);
        $order = $settings['source_order'] ?? 'latest';

        $query = $this->content_source_type::query();

        switch ($order) {
            case 'oldest':
                $query->oldest();
                break;
            case 'name_asc':
                $nameCol = $this->content_source_type === 'App\\Models\\Person' ? 'first_name' : 'name';
                $query->orderBy($nameCol, 'asc');
                break;
            case 'name_desc':
                $nameCol = $this->content_source_type === 'App\\Models\\Person' ? 'first_name' : 'name';
                $query->orderBy($nameCol, 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        return $query->limit($limit)->get();
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
