<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetUserPasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:user-passwords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset passwords for all users to "unpad2000", except dayat.hidayat@unpad.ac.id';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $defaultPassword = 'unpad2000';
        $excludedEmail = 'dayat.hidayat@unpad.ac.id';

        $this->info("Attempting to reset passwords for all users (except {$excludedEmail})...");

        $users = User::where('email', '!=', $excludedEmail)->get();

        if ($users->isEmpty()) {
            $this->info('No users found to reset passwords for.');
            return;
        }

        foreach ($users as $user) {
            $user->password = Hash::make($defaultPassword);
            $user->save();
            $this->comment("Password for user '{$user->email}' has been reset.");
        }

        $this->info('Password reset process completed successfully!');
    }
}
