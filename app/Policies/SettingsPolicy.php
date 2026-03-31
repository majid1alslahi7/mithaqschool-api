<?php

namespace App\Policies;

use App\Models\User;

class SettingsPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('manage_school_settings');
    }

    public function view(User $user): bool
    {
        return $user->can('manage_school_settings');
    }

    public function update(User $user): bool
    {
        return $user->can('manage_school_settings');
    }
}
