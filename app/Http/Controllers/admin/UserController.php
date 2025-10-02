<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // الحصول على المعاملات
        $search = $request->get('search');
        $selectedRole = $request->get('role', 'all');

        // بناء الاستعلام
        $query = User::with('roles');

        // فلترة حسب الدور
        if ($selectedRole !== 'all') {
            $query->whereHas('roles', function($q) use ($selectedRole) {
                $q->where('name', $selectedRole);
            });
        }

        // البحث
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // ترتيب النتائج
        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        // الحصول على جميع الأدوار
        $roles = Role::all();

        // حساب الإحصائيات
        $usersCount = User::count();
        $activeUsersCount = User::whereNotNull('email_verified_at')->count();
        $adminsCount = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['admin', 'super_admin']);
        })->count();

        return view('dashboard.users.index', compact(
            'users',
            'roles',
            'selectedRole',
            'usersCount',
            'activeUsersCount',
            'adminsCount'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['string', Rule::exists('roles', 'name')],
            'email_verified' => ['boolean'],
        ], [
            'name.required' => 'الاسم مطلوب',
            'name.max' => 'الاسم لا يمكن أن يتجاوز 255 حرف',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
            'roles.required' => 'يجب اختيار دور واحد على الأقل',
            'roles.min' => 'يجب اختيار دور واحد على الأقل',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'email_verified_at' => $validated['email_verified'] ? now() : null,
            ]);

            $user->syncRoles($validated['roles']);

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'تم إنشاء المستخدم بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إنشاء المستخدم: ' . $e->getMessage());
        }
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['string', Rule::exists('roles', 'name')],
            'email_verified' => ['boolean'],
        ], [
            'name.required' => 'الاسم مطلوب',
            'name.max' => 'الاسم لا يمكن أن يتجاوز 255 حرف',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
            'roles.required' => 'يجب اختيار دور واحد على الأقل',
            'roles.min' => 'يجب اختيار دور واحد على الأقل',
        ]);

        try {
            DB::beginTransaction();

            $data = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];

            // تحديث كلمة المرور إذا تم إدخالها
            if (!empty($validated['password'])) {
                $data['password'] = Hash::make($validated['password']);
            }

            // تحديث حالة التفعيل
            if ($validated['email_verified']) {
                $data['email_verified_at'] = now();
            } else {
                $data['email_verified_at'] = null;
            }

            $user->update($data);
            $user->syncRoles($validated['roles']);

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'تم تحديث المستخدم بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث المستخدم: ' . $e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        // التحقق من صلاحية الحذف - فقط المديرون يمكنهم حذف المستخدمين
        $userRoles = auth()->user()->roles->pluck('name')->toArray();
        if (!in_array('admin', $userRoles) && !in_array('super_admin', $userRoles)) {
            return back()->with('error', 'ليس لديك صلاحية لحذف المستخدمين');
        }

        // منع حذف المستخدم الحالي
        if ($user->id === auth()->id()) {
            return back()->with('error', 'لا يمكنك حذف حسابك الخاص');
        }

        // منع حذف آخر مدير
        $adminCount = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['admin', 'super_admin']);
        })->count();

        if ($adminCount <= 1 && $user->hasAnyRole(['admin', 'super_admin'])) {
            return back()->with('error', 'لا يمكن حذف آخر مدير في النظام');
        }

        // منع حذف المستخدمين الذين لديهم صلاحية حذف المستخدمين
        $targetUserRoles = $user->roles->pluck('name')->toArray();
        if (in_array('admin', $targetUserRoles) || in_array('super_admin', $targetUserRoles)) {
            return back()->with('error', 'لا يمكن حذف مستخدم لديه صلاحية حذف المستخدمين');
        }

        try {
            DB::beginTransaction();

            $userName = $user->name;
            $user->delete();

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', "تم حذف المستخدم '{$userName}' بنجاح");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء حذف المستخدم: ' . $e->getMessage());
        }
    }
}
