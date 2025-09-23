<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure guard
        $guard = config('auth.defaults.guard', 'web');

        // Define permissions
        $permissions = [
            'breastfeeding.view',
            'breastfeeding.create',
            'breastfeeding.update',
            'breastfeeding.delete',
            'people.view',
            'people.create',
            'people.update',
            'people.delete',
            'roles.manage',
            'users.manage',
        ];

        foreach ($permissions as $perm) {
            Permission::findOrCreate($perm, $guard);
        }

        // Roles
        $admin = Role::findOrCreate('admin', $guard);
        $editor = Role::findOrCreate('editor', $guard);
        $viewer = Role::findOrCreate('viewer', $guard);

        $admin->givePermissionTo(Permission::all());
        $editor->syncPermissions([
            'breastfeeding.view', 'breastfeeding.create', 'breastfeeding.update',
            'people.view',
        ]);
        $viewer->syncPermissions([
            'breastfeeding.view',
            'people.view',
        ]);

        // Assign admin to first user if exists
        $firstUser = User::query()->first();
        if ($firstUser && !$firstUser->hasRole('admin')) {
            $firstUser->assignRole($admin);
        }
    }
}
