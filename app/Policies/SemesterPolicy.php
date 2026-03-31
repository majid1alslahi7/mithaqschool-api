<?php

namespace App\Policies;

use App\Models\Semester;
use App\Models\User;

class SemesterPolicy
{
    /**
     * عرض قائمة الفصول الدراسية
     */
    public function viewAny(User $user)
    {
        return $user->can('view_any_semester');
    }

    /**
     * عرض فصل دراسي محدد
     */
    public function view(User $user, Semester $semester)
    {
        // المدير: يرى كل الفصول
        if ($user->hasRole(['super-admin', 'admin'])) {
            return true;
        }

        // المعلم: يرى الفصول التابعة للعام النشط
        if ($user->hasRole('teacher')) {
            return $semester->academicYear->is_active == true;
        }

        // الطالب: يرى الفصول التابعة للعام النشط
        if ($user->hasRole('student')) {
            return $semester->academicYear->is_active == true;
        }

        // ولي الأمر: يرى الفصول التابعة للعام النشط
        if ($user->hasRole('guardian')) {
            return $semester->academicYear->is_active == true;
        }

        return false;
    }

    /**
     * إنشاء فصل دراسي جديد
     */
    public function create(User $user)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('create_semester');
    }

    /**
     * تحديث فصل دراسي
     */
    public function update(User $user, Semester $semester)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('update_semester');
    }

    /**
     * حذف فصل دراسي
     */
    public function delete(User $user, Semester $semester)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('delete_semester');
    }
}