<?php

namespace App\Console\Commands;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Console\Command;

class AssignAllPermissionsToUser extends Command
{
    protected $signature = 'user:assign-all-permissions {email}';
    protected $description = 'Assign all available permissions directly to a user';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found.");
            return 1;
        }

        $permissions = Permission::pluck('name')->toArray();

        if (empty($permissions)) {
            $this->error("No permissions found in the system.");
            return 1;
        }

        $user->syncPermissions($permissions);

        $this->info("All permissions assigned directly to user {$user->email} successfully.");
        return 0;
    }
}
