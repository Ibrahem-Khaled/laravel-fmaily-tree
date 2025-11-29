<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SitePasswordController extends Controller
{
    /**
     * عرض صفحة إدخال كلمة المرور
     */
    public function show()
    {
        // إذا كان المستخدم قد أدخل كلمة المرور بالفعل ولم تنته صلاحيتها
        if (session('site_password_verified')) {
            $lastVerified = session('site_password_verified_at');
            $timeout = config('site.password_session_timeout', 60);
            
            if ($lastVerified && now()->diffInMinutes($lastVerified) <= $timeout) {
                return redirect()->intended('/');
            }
        }

        $passwordLength = config('site.password_length', 6);
        return view('site-password', compact('passwordLength'));
    }

    /**
     * التحقق من كلمة المرور
     */
    public function verify(Request $request)
    {
        $passwordLength = config('site.password_length', 6);
        $storedPassword = config('site.password');

        // التحقق من وجود كلمة المرور في الإعدادات
        if (empty($storedPassword)) {
            return back()->with('error', 'لم يتم تعيين كلمة مرور في الإعدادات.');
        }

        // التحقق من صحة البيانات المدخلة
        $validator = Validator::make($request->all(), [
            'password' => [
                'required',
                'string',
                'size:' . $passwordLength,
                'regex:/^[0-9]+$/'
            ],
        ], [
            'password.required' => 'يرجى إدخال كلمة المرور.',
            'password.size' => 'يجب أن تتكون كلمة المرور من ' . $passwordLength . ' أرقام.',
            'password.regex' => 'يجب أن تتكون كلمة المرور من أرقام فقط.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // التحقق من صحة كلمة المرور
        if ($request->password !== $storedPassword) {
            return back()->with('error', 'كلمة المرور غير صحيحة.');
        }

        // حفظ حالة التحقق في الجلسة
        session([
            'site_password_verified' => true,
            'site_password_verified_at' => now()
        ]);

        return redirect()->intended('/')->with('success', 'تم التحقق بنجاح.');
    }
}

