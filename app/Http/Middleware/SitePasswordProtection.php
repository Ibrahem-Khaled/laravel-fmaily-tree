<?php

namespace App\Http\Middleware;

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
        if (!config('site.password_protection_enabled', false)) {
            return $next($request);
        }

        // السماح بالوصول إلى صفحة إدخال كلمة المرور
        if ($request->routeIs('site.password.show') || $request->routeIs('site.password.verify')) {
            return $next($request);
        }

        // التحقق من أن المستخدم قد أدخل كلمة المرور في الجلسة
        if (!session('site_password_verified')) {
            return redirect()->route('site.password.show');
        }

        // التحقق من انتهاء صلاحية الجلسة
        $lastVerified = session('site_password_verified_at');
        $timeout = config('site.password_session_timeout', 60); // بالدقائق
        
        if ($lastVerified) {
            $lastVerifiedTime = is_string($lastVerified) ? Carbon::parse($lastVerified) : $lastVerified;
            if (now()->diffInMinutes($lastVerifiedTime) > $timeout) {
                session()->forget(['site_password_verified', 'site_password_verified_at']);
                return redirect()->route('site.password.show')
                    ->with('error', 'انتهت صلاحية الجلسة. يرجى إدخال كلمة المرور مرة أخرى.');
            }
        }

        return $next($request);
    }
}

