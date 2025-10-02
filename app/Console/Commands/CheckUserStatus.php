<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckUserStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:check-status {--user-id= : Check specific user ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check user activation status in database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->option('user-id');

        if ($userId) {
            $user = User::find($userId);
            if (!$user) {
                $this->error("User with ID {$userId} not found.");
                return;
            }

            $this->info("User Status Check:");
            $this->line("ID: {$user->id}");
            $this->line("Name: {$user->name}");
            $this->line("Email: {$user->email}");
            $this->line("Email Verified At: " . ($user->email_verified_at ? $user->email_verified_at : 'NULL'));
            $this->line("Status: " . ($user->email_verified_at ? 'ACTIVE' : 'INACTIVE'));
        } else {
            $users = User::all();

            $this->info("All Users Status:");
            $this->table(
                ['ID', 'Name', 'Email', 'Email Verified At', 'Status'],
                $users->map(function ($user) {
                    return [
                        $user->id,
                        $user->name,
                        $user->email,
                        $user->email_verified_at ? $user->email_verified_at->format('Y-m-d H:i:s') : 'NULL',
                        $user->email_verified_at ? 'ACTIVE' : 'INACTIVE'
                    ];
                })
            );
        }
    }
}
