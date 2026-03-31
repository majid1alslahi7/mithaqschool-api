<?php

namespace App\Policies;

use App\Models\Grade;
use App\Models\User;

class GradePolicy
{
    /**
     * عرض قائمة الصفوف
     */
    public function viewAny(User $user)
    {
        return $user->can('view_any_grade');
    }

    /**
     * عرض صف محدد
     */
    public function view(User $user, Grade $grade)
    {
        // المدير: يرى كل الصفوف
        if ($user->hasRole(['super-admin', 'admin'])) {
            return true;
        }

        // المعلم: يرى الصفوف التي يدرس فيها
        if ($user->teacher) {
            return $grade->id === $user->teacher->grade_id;
        }

        // الطالب: يرى صفه فقط
        if ($user->student) {
            return $grade->id === $user->student->grade_id;
        }

        // ولي الأمر: يرى صف أبنائه
        if ($user->guardian) {
            $childrenGradeIds = $user->guardian->students->pluck('grade_id')->toArray();
            return in_array($grade->id, $childrenGradeIds);
        }

        return false;
    }

    /**
     * إنشاء صف جديد
     */
    public function create(User $user)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('create_grade');
    }

    /**
     * تحديث صف
     */
    public function update(User $user, Grade $grade)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('update_grade');
    }

    /**
     * حذف صف
     */
    public function delete(User $user, Grade $grade)
    {
        return $user->hasRole(['super-admin', 'admin']) && $user->can('delete_grade');
    }
}