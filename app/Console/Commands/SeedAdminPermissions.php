<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\AdminPermissionsSeeder;

class SeedAdminPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:seed-permissions
                            {--email= : Admin email address}
                            {--password= : Admin password}
                            {--name= : Admin name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed admin permissions and create admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('بدء إنشاء صلاحيات المدير...');

        // تحديث إعدادات المدير إذا تم تمريرها
        if ($this->option('email')) {
            config(['app.admin_email' => $this->option('email')]);
        }
        if ($this->option('password')) {
            config(['app.admin_password' => $this->option('password')]);
        }
        if ($this->option('name')) {
            config(['app.admin_name' => $this->option('name')]);
        }

        // تشغيل السيدر
        $seeder = new AdminPermissionsSeeder();
        $seeder->setCommand($this);
        $seeder->run();

        $this->info('تم إنشاء صلاحيات المدير بنجاح!');

        return Command::SUCCESS;
    }
}
