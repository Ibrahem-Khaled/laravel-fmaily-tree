<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class UserRegistrationController extends Controller
{
    /**
     * عرض صفحة تسجيل الأشخاص
     */
    public function show()
    {
        return view('users.register');
    }

    /**
     * معالجة بيانات التسجيل وحفظها
     */
    public function store(Request $request)
    {
        try {
            // التحقق من البيانات
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'age' => 'required|integer|min:1|max:150',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ], [
                'name.required' => 'الاسم الكامل مطلوب',
                'name.max' => 'الاسم لا يمكن أن يتجاوز 255 حرف',
                'age.required' => 'العمر مطلوب',
                'age.integer' => 'العمر يجب أن يكون رقماً صحيحاً',
                'age.min' => 'العمر يجب أن يكون على الأقل 1 سنة',
                'age.max' => 'العمر لا يمكن أن يتجاوز 150 سنة',
                'avatar.image' => 'يجب أن يكون الملف المرفوع صورة',
                'avatar.mimes' => 'نوع الصورة يجب أن يكون: jpeg, png, jpg, gif, webp',
                'avatar.max' => 'حجم الصورة لا يمكن أن يتجاوز 2 ميجابايت',
            ]);

            DB::beginTransaction();

            // معالجة رفع الصورة الشخصية
            $avatarPath = null;
            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                $avatarPath = $avatar->store('avatars', 'public');
            }

            // إنشاء المستخدم الجديد
            $user = User::create([
                'name' => $validated['name'],
                'age' => $validated['age'],
                'avatar' => $avatarPath,
                'role_id' => 1, // دور افتراضي
                'status' => 0, // غير نشط افتراضياً
            ]);

            DB::commit();

            return redirect()
                ->route('users.register')
                ->with('success', 'تم تسجيل البيانات بنجاح! شكراً لك.');

        } catch (ValidationException $e) {
            DB::rollBack();
            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            
            // في حالة وجود صورة مرفوعة وفشل الحفظ، احذفها
            if (isset($avatarPath) && Storage::disk('public')->exists($avatarPath)) {
                Storage::disk('public')->delete($avatarPath);
            }

            return back()
                ->with('error', 'حدث خطأ أثناء حفظ البيانات. يرجى المحاولة مرة أخرى.')
                ->withInput();
        }
    }
}
