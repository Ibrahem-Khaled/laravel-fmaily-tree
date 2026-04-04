<?php

namespace App\Http\Middleware;

use App\Services\SiteMobileAccessToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;

class SitePasswordProtection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // التحقق من أن حماية الموقع مفعلة
        if (! config('site.password_protection_enabled', false)) {
            return $next($request);
        }

        // السماح بالوصول إلى صفحة إدخال كلمة المرور
        if ($request->routeIs('site.password.show') || $request->routeIs('site.password.verify')) {
            return $next($request);
        }

        // استثناء: بيانات المسابقة بصيغة JSON تكون عامة بدون كلمة مرور الموقع
        if ($request->routeIs('competitions.json')) {
            return $next($request);
        }

        // تطبيقات الجوال: رمز من POST /api/site-password/verify (مجموعة Laravel api، بدون جلسة متصفح)
        $mobileToken = SiteMobileAccessToken::extractFromRequest($request);
        if (SiteMobileAccessToken::valid($mobileToken)) {
            return $next($request);
        }

        // التحقق من أن المستخدم قد أدخل كلمة المرور في الجلسة
        if (! session('site_password_verified')) {
            return $this->denySitePassword($request);
        }

        // التحقق من انتهاء صلاحية الجلسة
        $lastVerified = session('site_password_verified_at');
        $timeout = config('site.password_session_timeout', 60); // بالدقائق

        if ($lastVerified) {
            $lastVerifiedTime = is_string($lastVerified) ? Carbon::parse($lastVerified) : $lastVerified;
            if (now()->diffInMinutes($lastVerifiedTime) > $timeout) {
                session()->forget(['site_password_verified', 'site_password_verified_at']);

                return $this->denySitePassword($request, 'انتهت صلاحية الجلسة. يرجى إدخال كلمة المرور مرة أخرى.');
            }
        }

        return $next($request);
    }

    private function denySitePassword(Request $request, ?string $jsonMessage = null): Response
    {
        $message = $jsonMessage ?? 'مطلوب التحقق من كلمة مرور الموقع.';

        if ($this->sitePasswordDenialShouldBeJson($request)) {
            return response()->json([
                'message' => $message,
                'code' => 'SITE_PASSWORD_REQUIRED',
            ], 401);
        }

        if ($jsonMessage) {
            return redirect()->route('site.password.show')->with('error', $jsonMessage);
        }

        return redirect()->route('site.password.show');
    }

    /**
     * Any request under /api/… must not get the HTML password page (mobile/Postman often omit expectsJson).
     */
    private function sitePasswordDenialShouldBeJson(Request $request): bool
    {
        if ($request->expectsJson()) {
            return true;
        }

        $path = $request->path();

        return $path === 'api'
            || str_starts_with($path, 'api/')
            || $request->is('api/*')
            || $request->segment(1) === 'api';
    }
}
