<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ActivateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:activate {user-id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activate a user by ID';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user-id');

        $user = User::find($userId);
        if (!$user) {
            $this->error("User with ID {$userId} not found.");
            return;
        }

        $user->email_verified_at = now();
        $user->save();

        $this->info("User {$user->name} (ID: {$user->id}) has been activated.");

        // Verify the update
        $user->refresh();
        $this->line("Verification - Email Verified At: " . ($user->email_verified_at ? $user->email_verified_at->format('Y-m-d H:i:s') : 'NULL'));
    }
}
