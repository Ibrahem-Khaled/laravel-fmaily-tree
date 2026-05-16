<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Site Password Protection
    |--------------------------------------------------------------------------
    |
    | إعدادات حماية الموقع بكلمة مرور
    |
    */

    // تفعيل/تعطيل حماية الموقع
    'password_protection_enabled' => env('SITE_PASSWORD_PROTECTION_ENABLED', false),

    // كلمة المرور (يجب أن تكون أرقام فقط)
    'password' => env('SITE_PASSWORD', ''),

    // طول كلمة المرور (يُحدَّث تلقائياً عند الحفظ من لوحة التحكم)
    'password_length' => (int) env('SITE_PASSWORD_LENGTH', 6),

    // الحد الأدنى والأقصى المسموح به عند تعيين كلمة المرور من لوحة التحكم
    'password_min_length' => (int) env('SITE_PASSWORD_MIN_LENGTH', 3),
    'password_max_length' => (int) env('SITE_PASSWORD_MAX_LENGTH', 12),

    // مدة صلاحية الجلسة بالدقائق (بعدها يطلب إدخال كلمة المرور مرة أخرى)
    'password_session_timeout' => env('SITE_PASSWORD_SESSION_TIMEOUT', 60),
];

