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

    // طول كلمة المرور (عدد الأرقام)
    'password_length' => env('SITE_PASSWORD_LENGTH', 6),

    // مدة صلاحية الجلسة بالدقائق (بعدها يطلب إدخال كلمة المرور مرة أخرى)
    'password_session_timeout' => env('SITE_PASSWORD_SESSION_TIMEOUT', 60),
];

