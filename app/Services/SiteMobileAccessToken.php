<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * رموز وصول التطبيقات لمسارات الويب المحمية بكلمة مرور الموقع (بدون جلسة متصفح).
 */
class SiteMobileAccessToken
{
    public static function cacheKey(string $plainToken): string
    {
        return 'site_mobile_access:' . hash('sha256', $plainToken);
    }

    /**
     * @return array{token: string, expires_in: int}
     */
    public static function issue(): array
    {
        $plain = Str::random(64);
        $minutes = max(1, (int) config('site.password_session_timeout', 60));
        cache()->put(
            self::cacheKey($plain),
            ['verified_at' => now()->toIso8601String()],
            now()->addMinutes($minutes)
        );

        return [
            'token' => $plain,
            'expires_in' => $minutes * 60,
        ];
    }

    public static function revoke(?string $plainToken): void
    {
        if ($plainToken) {
            cache()->forget(self::cacheKey($plainToken));
        }
    }

    public static function valid(?string $plainToken): bool
    {
        if (!$plainToken || strlen($plainToken) < 40) {
            return false;
        }

        return cache()->has(self::cacheKey($plainToken));
    }

    public static function extractFromRequest(Request $request): ?string
    {
        return $request->bearerToken() ?? $request->header('X-Site-Access-Token');
    }
}
