<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;

class CheckUserPermissions extends Command
{
    protected $signature = 'user:check-permissions {email} {permission?}';
    protected $description = 'Check user permissions and roles';

    public function handle()
    {
        $email = $this->argument('email');
        $permissionName = $this->argument('permission');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email '{$email}' not found.");
            return 1;
        }

        $this->info("User: {$user->name} ({$user->email})");
        $this->line("Roles: " . $user->roles->pluck('name')->join(', '));
        $this->line("");

        if ($permissionName) {
            $hasPermission = $user->can($permissionName);
            $this->info("Permission '{$permissionName}': " . ($hasPermission ? '✅ YES' : '❌ NO'));
        } else {
            $this->info("All Permissions:");
            $permissions = Permission::all();
            foreach ($permissions as $permission) {
                $hasPermission = $user->can($permission->name);
                $status = $hasPermission ? '✅' : '❌';
                $this->line("  {$status} {$permission->name}");
            }
        }

        return 0;
    }
}
