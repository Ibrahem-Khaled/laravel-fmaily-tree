<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SiteMobileAccessToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SitePasswordApiController extends Controller
{
    /**
     * حالة الحماية وطول كلمة المرور (للبناء واجهة مثل صفحة الويب).
     */
    public function status(): JsonResponse
    {
        $enabled = (bool) config('site.password_protection_enabled', false);

        return response()->json([
            'success' => true,
            'protection_enabled' => $enabled,
            'password_length' => $enabled ? (int) config('site.password_length', 6) : null,
            'requires_password' => $enabled,
            'session_timeout_minutes' => (int) config('site.password_session_timeout', 60),
        ]);
    }

    /**
     * التحقق من كلمة المرور وإصدار رمز للتطبيق (نفس قواعد الويب).
     */
    public function verify(Request $request): JsonResponse
    {
        if (! config('site.password_protection_enabled', false)) {
            return response()->json([
                'success' => true,
                'message' => 'حماية الموقع غير مفعّلة.',
                'access_token' => null,
                'token_type' => null,
                'expires_in' => null,
            ]);
        }

        $passwordLength = max(1, min(32, (int) config('site.password_length', 6)));
        $storedPassword = config('site.password');

        if (empty($storedPassword)) {
            return response()->json([
                'success' => false,
                'message' => 'لم يتم تعيين كلمة مرور في إعدادات الموقع.',
            ], 503);
        }

        $validator = Validator::make($request->all(), [
            'password' => [
                'required',
                'string',
                'size:'.$passwordLength,
                'regex:/^[0-9]+$/',
            ],
        ], [
            'password.required' => 'يرجى إدخال كلمة المرور.',
            'password.size' => 'يجب أن تتكون كلمة المرور من '.$passwordLength.' أرقام.',
            'password.regex' => 'يجب أن تتكون كلمة المرور من أرقام فقط.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first('password'),
                'errors' => $validator->errors(),
            ], 422);
        }

        if ($request->input('password') !== $storedPassword) {
            return response()->json([
                'success' => false,
                'message' => 'كلمة المرور غير صحيحة.',
            ], 401);
        }

        try {
            $issued = SiteMobileAccessToken::issue();
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'code' => 'TOKEN_CACHE_FAILED',
                'message' => config('app.debug')
                    ? $e->getMessage()
                    : 'تعذر حفظ رمز الوصول. تحقق من إعدادات الكاش على الخادم (CACHE_DRIVER، Redis، أو صلاحيات storage).',
            ], 503);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم التحقق بنجاح.',
            'access_token' => $issued['token'],
            'token_type' => 'Bearer',
            'expires_in' => $issued['expires_in'],
        ]);
    }

    /**
     * إلغاء صلاحية الرمز الحالي (اختياري).
     */
    public function revoke(Request $request): JsonResponse
    {
        $token = SiteMobileAccessToken::extractFromRequest($request);
        if (! $token) {
            return response()->json([
                'success' => false,
                'message' => 'لم يُرسل رمز وصول.',
            ], 400);
        }

        SiteMobileAccessToken::revoke($token);

        return response()->json([
            'success' => true,
            'message' => 'تم إلغاء الرمز.',
        ]);
    }
}
