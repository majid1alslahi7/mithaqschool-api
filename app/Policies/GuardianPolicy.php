<?php

namespace App\Policies;

use App\Models\Guardian;
use App\Models\User;

class GuardianPolicy
{
    /**
     * عرض قائمة أولياء الأمور
     */
    public function viewAny(User $user)
    {
        return $user->can('view_any_guardian');
    }

    /**
     * عرض ولي أمر محدد
     */
    public function view(User $user, Guardian $guardian)
    {
        // المدير: يرى كل أولياء الأمور
        if ($user->hasRole(['super-admin', 'admin'])) {
            return true;
        }

        // المعلم: لا يرى أولياء الأمور
        if ($user->teacher) {
            return false;
        }

        // ولي الأمر: يرى نفسه فقط
        if ($user->guardian) {
            return $guardian->id === $user->guardian->id;
        }

        return false;
    }

    /**
     * إنشاء ولي أمر جديد
     */
    public function create(User $user)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('create_guardian');
    }

    /**
     * تحديث بيانات ولي أمر
     */
    public function update(User $user, Guardian $guardian)
    {
        if ($user->hasRole(['super-admin', 'admin'])) {
            return true;
        }
        
        // ولي الأمر يمكنه تحديث بياناته الشخصية فقط
        if ($user->guardian && $user->guardian->id === $guardian->id) {
            return $user->can('update_guardian');
        }
        
        return false;
    }

    /**
     * حذف ولي أمر
     */
    public function delete(User $user, Guardian $guardian)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('delete_guardian');
    }
}