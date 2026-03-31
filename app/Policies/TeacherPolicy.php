<?php

namespace App\Policies;

use App\Models\Teacher;
use App\Models\User;

class TeacherPolicy
{
    /**
     * عرض قائمة المعلمين
     */
    public function viewAny(User $user)
    {
        return $user->can('view_any_teacher');
    }

    /**
     * عرض معلم محدد
     */
    public function view(User $user, Teacher $teacher)
    {
        // المدير: يرى كل المعلمين
        if ($user->hasRole(['super-admin', 'admin'])) {
            return true;
        }

        // المعلم: يرى نفسه فقط
        if ($user->teacher) {
            return $teacher->id === $user->teacher->id;
        }

        return false;
    }

    /**
     * إنشاء معلم جديد
     */
    public function create(User $user)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('create_teacher');
    }

    /**
     * تحديث بيانات معلم
     */
    public function update(User $user, Teacher $teacher)
    {
        // المدير يمكنه تحديث أي معلم
        if ($user->hasRole(['super-admin', 'admin'])) {
            return true;
        }
        
        // المعلم يمكنه تحديث بياناته الشخصية فقط
        if ($user->teacher && $user->teacher->id === $teacher->id) {
            return $user->can('update_teacher');
        }
        
        return false;
    }

    /**
     * حذف معلم
     */
    public function delete(User $user, Teacher $teacher)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('delete_teacher');
    }
}