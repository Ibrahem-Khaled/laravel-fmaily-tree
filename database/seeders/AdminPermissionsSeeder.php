<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guard = config('auth.defaults.guard', 'web');

        // إنشاء جميع الصلاحيات تلقائياً من المسارات
        $this->createPermissionsFromRoutes($guard);

        // إنشاء الأدوار
        $this->createRoles($guard);

        // إنشاء حساب المدير الافتراضي
        $this->createAdminUser();
    }

    /**
     * إنشاء الصلاحيات تلقائياً من المسارات
     */
    private function createPermissionsFromRoutes($guard): void
    {
        // الحصول على جميع المسارات
        $routes = app('router')->getRoutes();

        $permissions = [];

        foreach ($routes as $route) {
            $middleware = $route->gatherMiddleware();

            foreach ($middleware as $middlewareItem) {
                if (is_string($middlewareItem) && str_contains($middlewareItem, 'permission:')) {
                    $permissionString = str_replace('permission:', '', $middlewareItem);
                    $routePermissions = explode('|', $permissionString);

                    foreach ($routePermissions as $permission) {
                        $permissions[] = trim($permission);
                    }
                }
            }
        }

        // إضافة صلاحيات إضافية مطلوبة
        $additionalPermissions = [
            'dashboard.view',
            'dashboard.stats',
            'system.settings',
            'system.logs.view',
            'reports.view',
            'audit.view',
        ];

        $permissions = array_merge($permissions, $additionalPermissions);
        $permissions = array_unique($permissions);

        // إنشاء الصلاحيات
        foreach ($permissions as $permission) {
            if (!empty($permission)) {
                Permission::findOrCreate($permission, $guard);
            }
        }

        $this->command->info('تم إنشاء ' . count($permissions) . ' صلاحية تلقائياً');
    }

    /**
     * إنشاء الأدوار
     */
    private function createRoles($guard): void
    {
        // دور المدير العام - يمتلك جميع الصلاحيات
        $superAdminRole = Role::findOrCreate('super_admin', $guard);
        $superAdminRole->syncPermissions(Permission::all());

        // دور المدير - يمتلك جميع الصلاحيات
        $adminRole = Role::findOrCreate('admin', $guard);
        $adminRole->syncPermissions(Permission::all());

        // دور المحرر - صلاحيات محدودة
        $editorRole = Role::findOrCreate('editor', $guard);
        $editorPermissions = Permission::whereIn('name', [
            'people.view', 'people.create', 'people.update',
            'marriages.view', 'marriages.create', 'marriages.update',
            'breastfeeding.view', 'breastfeeding.create', 'breastfeeding.update',
            'articles.view', 'articles.create', 'articles.update',
            'categories.view', 'categories.create', 'categories.update',
            'padges.view', 'padges.create', 'padges.update',
            'images.view', 'images.upload',
            'dashboard.view', 'dashboard.stats',
            'councils.view', 'councils.create', 'councils.update', 'councils.delete',
        ])->get();
        $editorRole->syncPermissions($editorPermissions);

        // دور المشاهد - صلاحيات القراءة فقط
        $viewerRole = Role::findOrCreate('viewer', $guard);
        $viewerPermissions = Permission::whereIn('name', [
            'people.view',
            'marriages.view',
            'breastfeeding.view',
            'articles.view',
            'categories.view',
            'padges.view',
            'images.view',
            'dashboard.view',
        ])->get();
        $viewerRole->syncPermissions($viewerPermissions);

        // دور المستخدم العادي
        $userRole = Role::findOrCreate('user', $guard);
        $userPermissions = Permission::whereIn('name', [
            'people.view',
            'articles.view',
            'categories.view',
            'padges.view',
            'images.view',
            'dashboard.view',
        ])->get();
        $userRole->syncPermissions($userPermissions);

        $this->command->info('تم إنشاء الأدوار بنجاح');
    }

    /**
     * إنشاء حساب المدير الافتراضي
     */
    private function createAdminUser(): void
    {
        $adminEmail = config('app.admin_email', 'admin@familytree.com');
        $adminPassword = config('app.admin_password', 'admin123');
        $adminName = config('app.admin_name', 'مدير النظام');

        $adminUser = User::where('email', $adminEmail)->first();

        if (!$adminUser) {
            $adminUser = User::create([
                'name' => $adminName,
                'email' => $adminEmail,
                'password' => Hash::make($adminPassword),
                'email_verified_at' => now(),
            ]);

            $this->command->info('تم إنشاء حساب المدير الافتراضي');
        }

        // تعيين دور المدير العام
        if (!$adminUser->hasRole('super_admin')) {
            $adminUser->assignRole('super_admin');
            $this->command->info('تم تعيين دور المدير العام للمستخدم');
        }

        // عرض معلومات الحساب
        $this->command->info('=== معلومات حساب المدير ===');
        $this->command->info('البريد الإلكتروني: ' . $adminEmail);
        $this->command->info('كلمة المرور: ' . $adminPassword);
        $this->command->warn('يرجى تغيير كلمة المرور بعد تسجيل الدخول الأول!');
        $this->command->info('========================');
    }
}
