<?php

namespace App\Policies;

use App\Models\GradesScale;
use App\Models\User;

class GradesScalePolicy
{
    /**
     * عرض قائمة سلالم التقديرات
     */
    public function viewAny(User $user)
    {
        return $user->can('view_any_grade_scale');
    }

    /**
     * عرض سلم تقديرات محدد
     */
    public function view(User $user, GradesScale $gradesScale)
    {
        // المدير والمعلم يمكنهم رؤية سلالم التقديرات
        if ($user->hasRole(['super-admin', 'admin', 'teacher'])) {
            return true;
        }

        return false;
    }

    /**
     * إنشاء سلم تقديرات جديد
     */
    public function create(User $user)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('create_grade_scale');
    }

    /**
     * تحديث سلم تقديرات
     */
    public function update(User $user, GradesScale $gradesScale)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('update_grade_scale');
    }

    /**
     * حذف سلم تقديرات
     */
    public function delete(User $user, GradesScale $gradesScale)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('delete_grade_scale');
    }
}