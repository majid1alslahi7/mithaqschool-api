<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class RolePolicy
{
    /**
     * عرض قائمة الأدوار
     */
    public function viewAny(User $user)
    {
        return $user->can('view_any_role');
    }

    /**
     * عرض دور محدد
     */
    public function view(User $user, Role $role)
    {
        return $user->can('view_role');
    }

    /**
     * إنشاء دور جديد
     */
    public function create(User $user)
    {
        return $user->can('create_role');
    }

    /**
     * تحديث دور
     */
    public function update(User $user, Role $role)
    {
        // لا يمكن تعديل دور super-admin
        if ($role->name === 'super-admin') {
            return false;
        }
        
        return $user->can('update_role');
    }

    /**
     * حذف دور
     */
    public function delete(User $user, Role $role)
    {
        // لا يمكن حذف دور super-admin والأدوار الأساسية
        if (in_array($role->name, ['super-admin', 'admin', 'teacher', 'student', 'guardian'])) {
            return false;
        }
        
        return $user->can('delete_role');
    }

    /**
     * تعيين صلاحيات للدور
     */
    public function assignPermissions(User $user, Role $role)
    {
        // لا يمكن تعديل صلاحيات super-admin
        if ($role->name === 'super-admin') {
            return false;
        }
        
        return $user->can('assign_permission');
    }
}