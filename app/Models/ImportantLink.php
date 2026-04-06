<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class ImportantLink extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'title',
        'url',
        'url_ios',
        'url_android',
        'type',
        'icon',
        'description',
        'image',
        'submitted_by_user_id',
        'status',
        'order',
        'is_active',
        'open_in_new_tab',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'open_in_new_tab' => 'boolean',
        'order' => 'integer',
    ];

    protected $appends = ['image_url'];

    protected static function booted(): void
    {
        static::deleting(function (ImportantLink $link) {
            foreach ($link->media as $media) {
                Storage::disk('public')->delete($media->path);
            }
            if ($link->image) {
                Storage::disk('public')->delete($link->image);
            }
        });
    }

    /**
     * مَن أضاف أو اقترح الرابط (من جدول المستخدمين)
     */
    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by_user_id');
    }

    public function media(): HasMany
    {
        return $this->hasMany(ImportantLinkMedia::class)->orderBy('sort_order');
    }

    /**
     * صورة الرابط أو الصورة الافتراضية حسب النوع (تطبيق / موقع)
     */
    public function getImageUrlAttribute(): string
    {
        $firstImage = $this->media->where('kind', 'image')->first();
        if ($firstImage) {
            return asset('storage/'.$firstImage->path);
        }
        if ($this->image) {
            return asset('storage/'.$this->image);
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
        return self::with('media')
            ->where('is_active', true)
            ->where('status', 'approved')
            ->orderBy('order')
            ->get();
    }

    /**
     * جلب الروابط المعتمدة فقط (لصفحة الروابط المهمة العامة)
     */
    public static function getApprovedActiveLinks()
    {
        return self::with(['submitter', 'media'])
            ->where('is_active', true)
            ->where('status', 'approved')
            ->orderBy('order')
            ->get();
    }
}
