<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportSetting extends Model
{
    protected $fillable = [
        'page_title',
        'page_subtitle',
        'intro_html',
    ];

    /**
     * السجل الوحيد لإعدادات صفحة الدعم (يُنشأ عبر الـ migration).
     */
    public static function singleton(): self
    {
        $row = static::query()->first();

        if ($row === null) {
            $row = static::create([
                'page_title' => 'الدعم الفني',
                'page_subtitle' => 'نسعد بخدمتك وإجابتك عن استفساراتك',
                'intro_html' => null,
            ]);
        }

        return $row;
    }
}
