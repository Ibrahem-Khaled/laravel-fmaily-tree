<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SitePasswordSettingsController extends Controller
{
    /**
     * عرض صفحة إعدادات حماية الموقع
     */
    public function index()
    {
        $passwordMinLength = (int) config('site.password_min_length', 3);
        $passwordMaxLength = (int) config('site.password_max_length', 12);

        $settings = [
            'enabled' => config('site.password_protection_enabled', false),
            'password' => config('site.password', ''),
            'password_min_length' => $passwordMinLength,
            'password_max_length' => $passwordMaxLength,
            'session_timeout' => config('site.password_session_timeout', 60),
        ];

        return view('dashboard.site-password-settings.index', compact('settings'));
    }

    /**
     * تحديث إعدادات حماية الموقع
     */
    public function update(Request $request)
    {
        $isEnabled = $request->has('enabled') && $request->enabled == '1';

        $passwordMinLength = (int) config('site.password_min_length', 3);
        $passwordMaxLength = (int) config('site.password_max_length', 12);

        $rules = [
            'enabled' => 'nullable',
            'session_timeout' => 'required|integer|min:5|max:1440', // من 5 دقائق إلى 24 ساعة
        ];

        $messages = [
            'session_timeout.required' => 'يجب تحديد مدة صلاحية الجلسة.',
            'session_timeout.min' => 'يجب أن تكون مدة الجلسة على الأقل 5 دقائق.',
            'session_timeout.max' => 'يجب أن تكون مدة الجلسة على الأكثر 1440 دقيقة (24 ساعة).',
        ];

        // إضافة قواعد التحقق لكلمة المرور فقط عند التفعيل
        if ($isEnabled) {
            $rules['password'] = 'required|string|regex:/^[0-9]+$/|min:'.$passwordMinLength.'|max:'.$passwordMaxLength;
            $messages['password.required'] = 'يجب إدخال كلمة المرور عند تفعيل الحماية.';
            $messages['password.regex'] = 'يجب أن تتكون كلمة المرور من أرقام فقط.';
            $messages['password.min'] = 'يجب أن تتكون كلمة المرور من '.$passwordMinLength.' أرقام على الأقل.';
            $messages['password.max'] = 'يجب ألا تتجاوز كلمة المرور '.$passwordMaxLength.' رقماً.';
        } else {
            $rules['password'] = 'nullable|string|regex:/^[0-9]*$/|max:'.$passwordMaxLength;
        }

        $request->validate($rules, $messages);

        $password = $request->password ?? '';
        $passwordLength = $password !== '' ? strlen($password) : (int) config('site.password_length', 6);

        // تحديث ملف .env
        $this->updateEnvFile([
            'SITE_PASSWORD_PROTECTION_ENABLED' => $request->has('enabled') ? 'true' : 'false',
            'SITE_PASSWORD' => $password,
            'SITE_PASSWORD_LENGTH' => $passwordLength,
            'SITE_PASSWORD_SESSION_TIMEOUT' => $request->session_timeout ?? 60,
        ]);

        // إعادة تحميل الإعدادات
        config(['site.password_protection_enabled' => $request->has('enabled')]);
        config(['site.password' => $password]);
        config(['site.password_length' => $passwordLength]);
        config(['site.password_session_timeout' => $request->session_timeout ?? 60]);

        return redirect()->route('dashboard.site-password-settings.index')
            ->with('success', 'تم تحديث إعدادات حماية الموقع بنجاح.');
    }

    /**
     * تحديث ملف .env
     */
    private function updateEnvFile(array $data)
    {
        $envPath = base_path('.env');

        if (!File::exists($envPath)) {
            return false;
        }

        $envContent = File::get($envPath);
        $lines = explode("\n", $envContent);
        $updated = [];

        foreach ($lines as $line) {
            $line = trim($line);

            // تخطي الأسطر الفارغة والتعليقات
            if (empty($line) || strpos($line, '#') === 0) {
                $updated[] = $line;
                continue;
            }

            // البحث عن المفتاح في السطر
            $found = false;
            foreach ($data as $key => $value) {
                if (strpos($line, $key . '=') === 0) {
                    // تحديث القيمة
                    $updated[] = $key . '=' . $value;
                    unset($data[$key]); // إزالة المفتاح من المصفوفة لأنه تم تحديثه
                    $found = true;
                    break;
                }
            }

            // إذا لم يتم العثور على المفتاح، أضف السطر كما هو
            if (!$found) {
                $updated[] = $line;
            }
        }

        // إضافة المفاتيح الجديدة التي لم تكن موجودة
        foreach ($data as $key => $value) {
            $updated[] = $key . '=' . $value;
        }

        File::put($envPath, implode("\n", $updated));

        return true;
    }
}

