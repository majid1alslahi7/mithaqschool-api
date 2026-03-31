<?php

namespace App\Policies;

use App\Models\User;

class BackupPolicy
{
    public function create(User $user): bool
    {
        return $user->can('perform_backup');
    }

    public function restore(User $user): bool
    {
        return $user->can('restore_from_backup');
    }

    public function viewAny(User $user): bool
    {
        return $user->can('perform_backup');
    }

    public function delete(User $user): bool
    {
        return $user->can('perform_backup');
    }
}
