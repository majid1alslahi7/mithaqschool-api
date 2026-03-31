<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * عرض قائمة المستخدمين
     */
    public function viewAny(User $user)
    {
        return $user->can('view_any_user');
    }

    /**
     * عرض مستخدم محدد
     */
    public function view(User $user, User $targetUser)
    {
        // المدير يمكنه رؤية كل المستخدمين
        if ($user->hasRole(['super-admin', 'admin'])) {
            return true;
        }

        // المستخدم يمكنه رؤية نفسه فقط
        return $user->id === $targetUser->id;
    }

    /**
     * إنشاء مستخدم جديد
     */
    public function create(User $user)
    {
        return $user->can('create_user');
    }

    /**
     * تحديث مستخدم
     */
    public function update(User $user, User $targetUser)
    {
        // المدير يمكنه تحديث أي مستخدم
        if ($user->hasRole(['super-admin', 'admin'])) {
            return true;
        }

        // المستخدم يمكنه تحديث نفسه فقط
        return $user->id === $targetUser->id && $user->can('update_user');
    }

    /**
     * حذف مستخدم
     */
    public function delete(User $user, User $targetUser)
    {
        // لا يمكن حذف نفسه
        if ($user->id === $targetUser->id) {
            return false;
        }

        // فقط المدير يمكنه الحذف
        return $user->hasRole(['super-admin', 'admin']) && $user->can('delete_user');
    }

    /**
     * تعيين دور للمستخدم
     */
    public function assignRole(User $user, User $targetUser)
    {
        // لا يمكن تعديل دور super-admin
        if ($targetUser->hasRole('super-admin')) {
            return false;
        }

        return $user->hasRole(['super-admin', 'admin']) && $user->can('assign_permission');
    }
}