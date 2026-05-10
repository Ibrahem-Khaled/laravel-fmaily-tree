<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportChannel extends Model
{
    public const TYPE_EMAIL = 'email';

    public const TYPE_PHONE = 'phone';

    public const TYPE_WHATSAPP = 'whatsapp';

    public const TYPE_URL = 'url';

    public const TYPE_OTHER = 'other';

    protected $fillable = [
        'label',
        'type',
        'value',
        'icon',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * رابط قابل للاستخدام في واجهة الزائر (mailto:, tel:, wa.me, إلخ).
     */
    public function resolvedHref(): ?string
    {
        $raw = trim($this->value);

        if ($raw === '') {
            return null;
        }

        return match ($this->type) {
            self::TYPE_EMAIL => 'mailto:'.$raw,
            self::TYPE_PHONE => 'tel:'.preg_replace('/\s+/', '', $raw),
            self::TYPE_WHATSAPP => $this->whatsappHref($raw),
            self::TYPE_URL => $raw,
            self::TYPE_OTHER => str_starts_with($raw, 'http://') || str_starts_with($raw, 'https://')
                ? $raw
                : null,
            default => null,
        };
    }

    private function whatsappHref(string $digitsOrUrl): string
    {
        if (str_starts_with($digitsOrUrl, 'http://') || str_starts_with($digitsOrUrl, 'https://')) {
            return $digitsOrUrl;
        }

        $n = preg_replace('/\D+/', '', $digitsOrUrl);

        return 'https://wa.me/'.$n;
    }

    public static function typeLabels(): array
    {
        return [
            self::TYPE_EMAIL => 'بريد إلكتروني',
            self::TYPE_PHONE => 'هاتف',
            self::TYPE_WHATSAPP => 'واتساب',
            self::TYPE_URL => 'رابط',
            self::TYPE_OTHER => 'أخرى',
        ];
    }
}
