<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImportantLink extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'title',
        'url',
        'type',
        'icon',
        'description',
        'image',
        'submitted_by_user_id',
        'status',
        'order',
        'is_active',
        'open_in_new_tab'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'open_in_new_tab' => 'boolean',
        'order' => 'integer'
    ];

    protected $appends = ['image_url'];

    /**
     * مَن أضاف أو اقترح الرابط (من جدول المستخدمين)
     */
    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by_user_id');
    }

    /**
     * صورة الرابط أو الصورة الافتراضية حسب النوع (تطبيق / موقع)
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        if ($this->type === 'app') {
            return 'https://cdn-icons-png.flaticon.com/128/3437/3437364.png';
        }
        return 'https://cdn-icons-png.flaticon.com/128/1055/1055666.png';
    }

    /**
     * جلب الروابط النشطة المعتمدة مرتبة (للعرض العام)
     */
    public static function getActiveLinks()
    {
        return self::where('is_active', true)
            ->where('status', 'approved')
            ->orderBy('order')
            ->get();
    }

    /**
     * جلب الروابط المعتمدة فقط (لصفحة الروابط المهمة العامة)
     */
    public static function getApprovedActiveLinks()
    {
        return self::with('submitter')
            ->where('is_active', true)
            ->where('status', 'approved')
            ->orderBy('order')
            ->get();
    }
}
