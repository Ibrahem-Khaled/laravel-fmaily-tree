<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password as PasswordRules;

class AuthController extends Controller
{
    /**
     * عرض صفحة الملف الشخصي للمستخدم.
     */
    public function profile()
    {
        return view('Auth.profile');
    }

    /**
     * تحديث بيانات الملف الشخصي للمستخدم.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|unique:users,phone,' . $user->id,
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // التعامل مع رفع الصورة الشخصية الجديدة
        if ($request->hasFile('avatar')) {
            // حذف الصورة القديمة إذا كانت موجودة لتوفير المساحة
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            // تخزين الصورة الجديدة
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        // تحديث باقي البيانات باستخدام fill لتجنب التكرار
        $user->fill($request->only(['name', 'email', 'phone', 'address']));
        $user->save();

        return redirect()->back()->with('success', 'تم تحديث البيانات بنجاح.');
    }

    /**
     * تحديث كلمة مرور المستخدم.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            // استخدام كائن القواعد لتحسين قراءة الشروط
            'new_password' => ['required', 'string', PasswordRules::min(8)->letters()->numbers(), 'confirmed'],
        ]);

        $user = auth()->user();

        // التحقق من تطابق كلمة المرور الحالية
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'كلمة المرور الحالية غير صحيحة.');
        }

        // تحديث كلمة المرور
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'تم تغيير كلمة المرور بنجاح.');
    }

    /**
     * عرض صفحة تسجيل الدخول.
     */
    public function login()
    {
        // إذا كان المستخدم مسجل دخوله بالفعل، يتم توجيهه للوحة التحكم
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('Auth.login');
    }

    /**
     * معالجة طلب تسجيل الدخول.
     */
    public function customLogin(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email' => 'يجب إدخال بريد إلكتروني صالح.',
            'password.required' => 'كلمة المرور مطلوبة.',
            'password.min' => 'يجب أن تحتوي كلمة المرور على 6 أحرف على الأقل.',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        // *** إصلاح: محاولة تسجيل دخول المستخدم ***
        if (Auth::attempt($credentials, $remember)) {
            // تجديد جلسة المستخدم لمنع هجمات Session Fixation
            $request->session()->regenerate();
            // توجيه المستخدم إلى الصفحة التي كان يقصدها قبل تسجيل الدخول
            return redirect()->intended(route('dashboard'))->with('success', 'تم تسجيل الدخول بنجاح.');
        }

        // في حالة فشل تسجيل الدخول، العودة لصفحة الدخول مع رسالة خطأ
        return redirect()->back()->withErrors([
            'email' => 'بيانات تسجيل الدخول غير صحيحة.',
        ])->onlyInput('email');
    }

    /**
     * عرض صفحة إنشاء حساب جديد.
     */
    public function register()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('Auth.register');
    }

    /**
     * معالجة طلب إنشاء حساب جديد.
     */
    public function customRegister(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'address' => 'required|string|max:255',
            'password' => ['required', 'string', PasswordRules::min(6), 'confirmed'],
        ]);

        // استخدام Hash::make بدلاً من bcrypt
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
        ]);

        // تسجيل دخول المستخدم الجديد تلقائياً
        Auth::login($user);

        // *** تحسين: توجيه المستخدم إلى لوحة التحكم بدلاً من الصفحة السابقة ***
        return redirect()->route('dashboard')->with('success', 'تم إنشاء حسابك بنجاح.');
    }

    /**
     * تسجيل خروج المستخدم.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        // إبطال الجلسة الحالية
        $request->session()->invalidate();
        // إنشاء Token جديد للحماية من هجمات CSRF
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'تم تسجيل الخروج بنجاح.');
    }

    /**
     * حذف حساب المستخدم.
     */
    public function deleteAccount(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // تسجيل الخروج قبل الحذف
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // حذف الصورة الشخصية من التخزين
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return redirect()->route('login')->with('success', 'تم حذف الحساب بنجاح.');
    }

    /**
     * عرض صفحة طلب استعادة كلمة المرور.
     */
    public function forgetPassword()
    {
        return view('Auth.forgetPassword');
    }

    /**
     * معالجة طلب استعادة كلمة المرور.
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate(['email' => 'required|email']);

        // *** إصلاح: استخدام نظام استعادة كلمة المرور المدمج في لارافيل ***
        // هذا النظام يتولى إنشاء التوكن وإرسال الإيميل بشكل آمن
        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return redirect()->back()->with('success', 'تم إرسال رابط استعادة كلمة المرور إلى بريدك الإلكتروني.');
        }

        // في حالة فشل إرسال الرابط (مثلاً، الإيميل غير موجود)
        return redirect()->back()->withErrors(['email' => __($status)]);
    }
}
